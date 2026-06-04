<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $cloudinaryUrl = env('CLOUDINARY_URL');
        if (empty($cloudinaryUrl)) {
            throw new \Exception("Cloudinary URL is not configured in .env file.");
        }

        $this->cloudinary = new Cloudinary($cloudinaryUrl);
    }

    /**
     * Upload an image to Cloudinary
     * 
     * @param string $filePath The absolute path to the temporary file
     * @param string $folder The folder inside Cloudinary to store the image
     * @return string Returns the secure URL of the uploaded image
     */
    public function uploadImage(string $filePath, string $folder = 'avatars'): string
    {
        $response = $this->cloudinary->uploadApi()->upload($filePath, [
            'folder' => "appointment_system/$folder",
            'resource_type' => 'image',
            'transformation' => [
                'width' => 400,
                'height' => 400,
                'crop' => 'fill',
                'gravity' => 'face'
            ] // Automatically crop to a square focusing on the face!
        ]);

        return $response['secure_url'];
    }
}
