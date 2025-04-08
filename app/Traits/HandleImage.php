<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandleImage
{
    public function uploadImage(UploadedFile $file, string $folder): string
    {
        return $file->store("uploads/$folder", 'public');
    }
}
