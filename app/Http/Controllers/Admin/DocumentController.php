<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    /**
     * Serve private vendor documents to authenticated admins.
     */
    public function show($filename)
    {
        $path = 'private/vendor-documents/' . $filename;

        if (!Storage::exists($path)) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        $response = response($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
