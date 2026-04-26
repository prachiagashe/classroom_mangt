<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Optimize profile image.
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string Path to the optimized image
     */
    public static function optimize($file, $folder = 'profile_images')
    {
        // Create image manager with GD driver
        $manager = new ImageManager(new Driver());
        
        // Read image from file path
        $image = $manager->read($file->getRealPath());
        
        // Resize to fit within 300x300 while maintaining aspect ratio
        // If the image is smaller than 300x300, it won't be enlarged
        if ($image->width() > 300 || $image->height() > 300) {
            $image->scale(300, 300);
        }
        
        // Generate unique filename with .jpg extension
        $filename = Str::random(20) . '_' . time() . '.jpg';
        $path = $folder . '/' . $filename;
        
        // Encode as JPEG with 70% quality
        $encoded = $image->toJpeg(70);
        
        // Ensure directory exists on public disk
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }
        
        // Save to public disk
        Storage::disk('public')->put($path, (string) $encoded);
        
        return $path;
    }
}
