<?php

namespace App\Traits;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait HandleImage
{
    public function uploadImage(UploadedFile $image, string $folder = 'images/upload', int $width = 800, int $height = 600): string
    {
        $filename = time() . '_' . $image->getClientOriginalName();

        $path = public_path($folder);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $manager = new ImageManager(new Driver());

        $img = $manager->read($image->getRealPath());

        $img = $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $img->save($path . '/' . $filename);
        return  $filename;
    }


    public function deleteImage(string $filename, string $folder = 'images/upload'): void
    {
        $path = public_path($folder . '/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
