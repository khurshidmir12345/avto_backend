<?php

namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Support\Str;

class R2ImageService
{
    private S3Client $client;

    private string $bucket;

    private string $disk;

    private int $presignedExpiryMinutes;

    public function __construct()
    {
        $config = config('filesystems.disks.r2');
        $this->bucket = $config['bucket'];
        $this->disk = config('moshina_elon.images.disk', 'r2');
        $this->presignedExpiryMinutes = config('moshina_elon.images.presigned_expiry_minutes', 15);

        $this->client = new S3Client([
            'version' => 'latest',
            'region' => $config['region'] ?? 'auto',
            'endpoint' => $config['endpoint'],
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
            'use_path_style_endpoint' => $config['use_path_style_endpoint'] ?? false,
        ]);
    }

    /**
     * Presigned PUT URL yaratadi. Client to'g'ridan-to'g'ri R2 ga yuklaydi.
     */
    public function createPresignedUploadUrl(string $imageKey, string $contentType): string
    {
        $cmd = $this->client->getCommand('PutObject', [
            'Bucket' => $this->bucket,
            'Key' => $imageKey,
            'ContentType' => $contentType,
            'CacheControl' => 'public, max-age=31536000',
        ]);

        $request = $this->client->createPresignedRequest(
            $cmd,
            "+{$this->presignedExpiryMinutes} minutes"
        );

        return (string) $request->getUri();
    }

    /**
     * Yangi image_key generatsiya qiladi.
     * cars/{car_id}/{uuid}.jpg yoki pending/{user_id}/{uuid}.jpg
     */
    public function generateImageKey(?int $carId, ?int $userId, string $extension = 'jpg'): string
    {
        $uuid = Str::random(12);
        $ext = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']) ? strtolower($extension) : 'jpg';
        if ($ext === 'jpeg') {
            $ext = 'jpg';
        }

        if ($carId !== null) {
            return "cars/{$carId}/{$uuid}.{$ext}";
        }

        return "pending/{$userId}/{$uuid}.{$ext}";
    }

    /**
     * Rasmni R2 dan o'chiradi.
     */
    public function delete(string $imageKey): bool
    {
        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $imageKey,
            ]);
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
