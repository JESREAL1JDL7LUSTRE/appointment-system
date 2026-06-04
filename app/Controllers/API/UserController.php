<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Services\CloudinaryService;

class UserController extends ResourceController
{
    /**
     * POST /api/user/avatar
     * Accepts a multipart/form-data upload with a 'profile_picture' file
     */
    public function uploadAvatar()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->failUnauthorized("You must be logged in to upload an avatar.");
        }

        $rules = [
            'profile_picture' => 'uploaded[profile_picture]|is_image[profile_picture]|max_size[profile_picture,5120]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $file = $this->request->getFile('profile_picture');

        if (!$file->isValid() || $file->hasMoved()) {
            return $this->fail("Invalid file upload.");
        }

        try {
            // Upload to Cloudinary
            $cloudinaryService = new CloudinaryService();
            $secureUrl = $cloudinaryService->uploadImage($file->getTempName(), 'avatars');

            // Save URL to database
            $userModel = new UserModel();
            $userModel->update($userId, ['profile_picture' => $secureUrl]);

            return $this->respond([
                'message' => 'Profile picture updated successfully',
                'profile_picture_url' => $secureUrl
            ], 200);

        } catch (\Exception $e) {
            return $this->failServerError("Failed to upload image: " . $e->getMessage());
        }
    }
}
