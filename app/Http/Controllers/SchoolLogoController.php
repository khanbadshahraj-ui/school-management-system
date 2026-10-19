<?php

namespace App\Http\Controllers;

use App\Contracts\SchoolLogoStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SchoolLogoController extends Controller
{
    public function show(Request $request, SchoolLogoStorage $storage): Response
    {
        $logo = $storage->get((int) $request->user()->school_id);
        abort_if($logo === null, 404);

        return response($logo['content'], 200, ['Content-Type' => $logo['mime_type'], 'Cache-Control' => 'private, no-store', 'ETag' => '"'.$logo['content_hash'].'"', 'X-Content-Type-Options' => 'nosniff']);
    }

    public function store(Request $request, SchoolLogoStorage $storage): RedirectResponse
    {
        abort_unless($request->user()->role === 'school_admin', 403);
        $request->validate(['logo' => ['required', 'file', 'mimes:png,jpg,jpeg', 'mimetypes:image/png,image/jpeg', 'max:128', 'dimensions:min_width=16,min_height=16,max_width=1024,max_height=1024']]);
        $file = $request->file('logo');
        DB::transaction(function () use ($request, $storage, $file) {
            $storage->put((int) $request->user()->school_id, $file->getContent(), $file->getMimeType());
            DB::table('audit_events')->insert(['school_id' => $request->user()->school_id, 'user_id' => $request->user()->id, 'action' => 'school.logo.updated', 'created_at' => now()]);
        });

        return back()->with('success', 'School monogram saved durably.');
    }
}
