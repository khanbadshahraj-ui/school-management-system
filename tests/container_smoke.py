"""Exercise the actual production image through a simulated TLS-terminating proxy."""
import http.cookiejar
import json
import sys
import time
import urllib.error
import urllib.parse
import urllib.request

base = "http://127.0.0.1:10000"
jar = http.cookiejar.CookieJar()
class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None


client = urllib.request.build_opener(NoRedirect(), urllib.request.HTTPCookieProcessor(jar))


def request(path, data=None, inertia=False):
    headers = {"Host": "school.example.test", "X-Forwarded-Proto": "https", "Accept": "application/json" if inertia else "text/html"}
    # Render terminates TLS before forwarding HTTP. Include secure cookies explicitly
    # because this CI-only connection exercises that internal HTTP hop on loopback.
    headers["Cookie"] = "; ".join(f"{cookie.name}={cookie.value}" for cookie in jar)
    token = next((cookie.value for cookie in jar if cookie.name == "XSRF-TOKEN"), None)
    if token:
        headers["X-XSRF-TOKEN"] = urllib.parse.unquote(token)
    if data is not None:
        headers["Content-Type"] = "application/x-www-form-urlencoded"
        data = urllib.parse.urlencode(data).encode()
    if inertia:
        headers["X-Inertia"] = "true"
    try:
        return client.open(urllib.request.Request(base + path, data=data, headers=headers), timeout=15)
    except urllib.error.HTTPError as error:
        if error.code in (302, 303):
            return error
        raise


for attempt in range(90):
    try:
        assert request("/up").status == 200
        break
    except (OSError, AssertionError):
        if attempt == 89:
            raise
        time.sleep(2)
assert request("/ready").status == 200
request("/login")
request("/login", {"email": "owner@example.test", "password": "CI-only-owner-password"})
page = json.load(request("/dashboard", inertia=True))
assert page["component"] == "Dashboard", "Owner could not sign in"
assert page["props"]["auth"]["user"]["role"] == "school_admin"
assert page["props"]["demo"] is True
assert len(jar) >= 2

# Use the application model layer only to insert an easily recognized restart marker.
# Its persistence is then checked by the authenticated HTTP endpoint after replacement.
import subprocess
if sys.argv[1] == "initial":
    code = "$app=require 'bootstrap/app.php'; $app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap(); Illuminate\\Support\\Facades\\DB::table('schools')->update(['name'=>'Restart persistence verified']);"
    subprocess.run(["docker", "exec", "school-app", "php", "-r", "require 'vendor/autoload.php'; " + code], check=True)
else:
    assert page["props"]["school"]["name"] == "Restart persistence verified", "Bootstrap overwrote persisted branding"
request("/logout", {})
print(f"Production image {sys.argv[1]}: health, readiness, owner login, permissions, logout and persistence passed.")
