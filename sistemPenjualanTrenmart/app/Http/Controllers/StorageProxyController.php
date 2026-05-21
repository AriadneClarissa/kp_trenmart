<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageProxyController extends Controller
{
    public function show($path)
    {
        $path = ltrim($path, '/');
        $full = storage_path('app/public/' . $path);

        if (!file_exists($full)) {
            abort(404);
        }

        $mime = mime_content_type($full) ?: 'application/octet-stream';

        return response()->file($full, ['Content-Type' => $mime]);
    }
}
