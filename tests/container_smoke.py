"""Exercise the production image through a simulated TLS-terminating proxy."""
import http.cookiejar
import json
import re
import struct
import sys
import time
import urllib.error
import urllib.parse
import urllib.request
import zlib

base = "http://127.0.0.1:10000"
version = None
jar = http.cookiejar.LWPCookieJar("/tmp/school-ci-cookies")
replacement = sys.argv[1] == "replacement"
if replacement:
    jar.load(ignore_discard=True, ignore_expires=True)


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None


client = urllib.request.build_opener(NoRedirect(), urllib.request.HTTPCookieProcessor(jar))


def request(path, data=None, inertia=False, content_type=None):
    headers = {"Host": "school.example.test", "X-Forwarded-Proto": "https", "Accept": "application/json" if inertia else "text/html"}
    # Render terminates TLS before the internal HTTP hop; CI simulates that hop.
    headers["Cookie"] = "; ".join(f"{cookie.name}={cookie.value}" for cookie in jar)
    token = next((cookie.value for cookie in jar if cookie.name == "XSRF-TOKEN"), None)
    if token:
        headers["X-XSRF-TOKEN"] = urllib.parse.unquote(token)
    if data is not None:
        headers["Content-Type"] = content_type or "application/x-www-form-urlencoded"
        if isinstance(data, dict):
            data = urllib.parse.urlencode(data).encode()
    if inertia:
        headers["X-Inertia"] = "true"
        headers["X-Inertia-Version"] = version or ""
    try:
        return client.open(urllib.request.Request(base + path, data=data, headers=headers), timeout=15)
    except urllib.error.HTTPError as error:
        if error.code in (302, 303):
            return error
        raise


def png():
    def chunk(kind, data):
        return struct.pack("!I", len(data)) + kind + data + struct.pack("!I", zlib.crc32(kind + data) & 0xffffffff)
    return b"\x89PNG\r\n\x1a\n" + chunk(b"IHDR", struct.pack("!2I5B", 16, 16, 8, 2, 0, 0, 0)) + chunk(b"IDAT", zlib.compress((b"\0" + bytes((30, 100, 200)) * 16) * 16)) + chunk(b"IEND", b"")


for attempt in range(90):
    try:
        assert request("/up").status == 200
        break
    except (OSError, AssertionError):
        if attempt == 89:
            raise
        time.sleep(2)
assert request("/ready").status == 200
if not replacement:
    html = request("/login").read().decode()
    for asset in set(re.findall(r'/build/assets/[^"\s<>]+\.(?:js|css)', html)):
        assert request(asset).status == 200
    assert "/build/assets/" in html, "Compiled assets missing"
    assert request("/login", {"email": "owner@example.test", "password": "CI-only-owner-password"}).status == 302
dashboard = request("/dashboard")
assert dashboard.status == 200, "Owner session did not survive"
markup = dashboard.read().decode()
initial_page = json.loads(re.search(r'<script[^>]*data-page="[^"]+"[^>]*>(.*?)</script>', markup, re.S).group(1))
version = initial_page["version"]
page = json.load(request("/dashboard", inertia=True))
assert page["component"] == "Dashboard", "Owner session did not survive"
assert page["props"]["auth"]["user"]["role"] == "school_admin"
assert page["props"]["demo"] is True
if not replacement:
    school = page["props"]["school"]
    assert request("/settings", {"_method": "PATCH", "name": "Restart persistence verified", "primary_color": school["primary_color"], "locale": school["locale"], "timezone": school["timezone"], "currency": school["currency"]}).status in (302, 303)
    boundary = "school-ci-image-boundary"
    body = (f'--{boundary}\r\nContent-Disposition: form-data; name="logo"; filename="logo.png"\r\nContent-Type: image/png\r\n\r\n'.encode() + png() + f'\r\n--{boundary}--\r\n'.encode())
    assert request("/school/logo", body, content_type=f"multipart/form-data; boundary={boundary}").status in (302, 303)
    assert request("/school/logo").read() == png()
    jar.save(ignore_discard=True, ignore_expires=True)
else:
    assert page["props"]["school"]["name"] == "Restart persistence verified", "Bootstrap overwrote persisted branding"
    assert request("/school/logo").read() == png(), "Logo lost on replacement"
    assert request("/logout", {}).status == 302
    assert request("/dashboard").headers["Location"].endswith("/login")
print(f"Production image {sys.argv[1]}: health, assets, owner permissions, branding, logo and database session persistence passed.")
