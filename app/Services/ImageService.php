<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{

    protected const DISK = 'images';

    /**
     * Save an image to disk
     *
     * @param mixed $image uploaded image file
     * @param string $path relative path
     * @return string filename
     */
    public static function save(mixed $image, string $path = ''): string
    {
        $filename = Str::uuid() . '.' . $image->extension();
        $image->storeAs($path, $filename, self::DISK);

        return $filename;
    }

    /**
     * Save an image to disk from the given url
     *
     * @param mixed $url of the image file to be saved
     * @param string $path relative path
     * @return string filename
     */
    public static function saveFromUrl($url, string $path = ''): string
    {
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $fileContents = file_get_contents($url, false, stream_context_create($arrContextOptions));

        $filename = Str::uuid() . '.png';
        Storage::disk(self::DISK)->put($path . '/' . $filename, $fileContents);

        return $filename;
    }

    /**
     * Delete an image file from disk
     *
     * @param string $filename relative path
     * @return void
     */
    public static function delete(string $filename): void
    {
        if (Storage::disk(self::DISK)->exists($filename)) {
            Storage::disk(self::DISK)->delete($filename);
        }
    }

    /**
     * Get the url for the specified image file
     *
     * @param string $filename relative path
     * @return string
     */
    public static function url(string $filename): string
    {
        return Storage::disk(self::DISK)->url($filename);
    }

    /**
     * Get full path to the specified image file
     *
     * @param string $filename
     * @return string
     */
    public static function fullPath(string $filename): string
    {
        return Storage::disk(self::DISK)->path($filename);
    }
}
