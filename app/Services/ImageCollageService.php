<?php

namespace App\Services;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class ImageCollageService
{
    private const CANVAS_WIDTH = 1080;
    private const GAP = 6;
    private const BG_COLOR = '#0a1f18';
    private const JPEG_QUALITY = 85;

    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Rasmlar massividan collage yaratadi.
     *
     * @param  array<string>  $imageDataArray  Rasm binary data massivi
     * @return string JPEG binary string
     */
    public function create(array $imageDataArray): string
    {
        $count = count($imageDataArray);

        if ($count === 0) {
            throw new \InvalidArgumentException('Kamida bitta rasm kerak');
        }

        if ($count === 1) {
            return $this->createSingle($imageDataArray[0]);
        }

        $layout = $this->getLayout($count);
        $rowMeta = $this->calculateRowMeta($layout);
        $canvasHeight = $this->calculateCanvasHeight($rowMeta);

        $canvas = $this->manager->create(self::CANVAS_WIDTH, $canvasHeight);
        $canvas->fill(self::BG_COLOR);

        $imageIndex = 0;
        $y = 0;

        foreach ($rowMeta as $row) {
            $x = 0;
            for ($col = 0; $col < $row['cols']; $col++) {
                if ($imageIndex >= $count) {
                    break;
                }

                $img = $this->readAndCrop($imageDataArray[$imageIndex], $row['cellW'], $row['cellH']);
                $canvas->place($img, 'top-left', $x, $y);

                $x += $row['cellW'] + self::GAP;
                $imageIndex++;
            }

            $y += $row['cellH'] + self::GAP;
        }

        return $canvas->toJpeg(self::JPEG_QUALITY)->toString();
    }

    /**
     * Bitta rasmni standart o'lchamga keltiradi.
     */
    private function createSingle(string $imageData): string
    {
        $image = $this->manager->read($imageData);
        $image->cover(self::CANVAS_WIDTH, self::CANVAS_WIDTH);

        return $image->toJpeg(self::JPEG_QUALITY)->toString();
    }

    private function readAndCrop(string $data, int $width, int $height): ImageInterface
    {
        $image = $this->manager->read($data);
        $image->cover($width, $height);

        return $image;
    }

    /**
     * Rasm soniga qarab grid layout qaytaradi.
     * Har bir element bir qatordagi ustunlar soni.
     *
     * @return array<int> [2, 3] = birinchi qator 2 ta, ikkinchi qator 3 ta
     */
    private function getLayout(int $count): array
    {
        return match ($count) {
            1 => [1],
            2 => [2],
            3 => [3],
            4 => [2, 2],
            5 => [2, 3],
            6 => [3, 3],
            7 => [4, 3],
            default => [4, 3],
        };
    }

    /**
     * Har bir qator uchun hujayra o'lchamlarini hisoblaydi.
     *
     * @return array<array{cols: int, cellW: int, cellH: int}>
     */
    private function calculateRowMeta(array $layout): array
    {
        $meta = [];

        foreach ($layout as $cols) {
            $totalGap = self::GAP * max(0, $cols - 1);
            $cellW = (int) floor((self::CANVAS_WIDTH - $totalGap) / $cols);
            $meta[] = [
                'cols' => $cols,
                'cellW' => $cellW,
                'cellH' => $cellW,
            ];
        }

        return $meta;
    }

    private function calculateCanvasHeight(array $rowMeta): int
    {
        $height = 0;

        foreach ($rowMeta as $i => $row) {
            $height += $row['cellH'];
            if ($i < count($rowMeta) - 1) {
                $height += self::GAP;
            }
        }

        return $height;
    }
}
