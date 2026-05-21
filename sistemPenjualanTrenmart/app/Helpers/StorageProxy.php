<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageProxy
{
    public static function url(?string $path): string
    {
        if (empty($path)) {
            return asset('images/no-image.png');
        }

        $public = public_path('storage/' . $path);
        if (file_exists($public)) {
            return asset('storage/' . $path);
        }

        // If file exists in storage disk, use proxy route
        if (Storage::disk('public')->exists($path)) {
            return url('/storage-proxy/' . $path);
        }

        return asset('images/no-image.png');
    }
}
