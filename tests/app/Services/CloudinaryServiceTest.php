<?php

namespace App\Services;

use CodeIgniter\Test\CIUnitTestCase;

class CloudinaryServiceTest extends CIUnitTestCase
{
    public function testConstructorThrowsExceptionIfEnvMissing()
    {
        // Temporarily clear env
        $oldEnv = getenv('CLOUDINARY_URL');
        putenv('CLOUDINARY_URL=');
        $_ENV['CLOUDINARY_URL'] = '';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cloudinary URL is not configured');

        $service = new CloudinaryService();

        // Restore env
        putenv('CLOUDINARY_URL=' . $oldEnv);
        $_ENV['CLOUDINARY_URL'] = $oldEnv;
    }
}
