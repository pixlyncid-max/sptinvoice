<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class GenerateSocialIconsSeeder extends Seeder
{
    public function run(): void
    {
        $dirs = [
            public_path('images/social'),
            public_path('storage/social'),
            storage_path('app/public/social'),
        ];

        foreach ($dirs as $dir) {
            if (!File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true, true);
            }
        }

        $icons = [
            'whatsapp' => [
                'bg' => [37, 211, 102], // #25D366
                'draw' => function($im, $w, $h, $white) {
                    // Draw phone bubble
                    imagefilledellipse($im, 32, 32, 40, 40, $white);
                    $green = imagecolorallocate($im, 37, 211, 102);
                    imagefilledellipse($im, 32, 32, 32, 32, $green);
                    // Phone handset in white
                    imagefilledellipse($im, 28, 30, 8, 14, $white);
                    imagefilledellipse($im, 36, 34, 8, 14, $white);
                    imagefilledrectangle($im, 28, 32, 36, 36, $white);
                }
            ],
            'instagram' => [
                'bg' => [225, 48, 108], // #E1306C
                'draw' => function($im, $w, $h, $white) {
                    // Rounded rect camera
                    imagesetthickness($im, 3);
                    imagearc($im, 32, 32, 32, 32, 0, 360, $white);
                    imageellipse($im, 32, 32, 14, 14, $white);
                    imagefilledellipse($im, 41, 23, 4, 4, $white);
                }
            ],
            'facebook' => [
                'bg' => [24, 119, 242], // #1877F2
                'draw' => function($im, $w, $h, $white) {
                    // Bold 'f'
                    imagefilledrectangle($im, 30, 16, 36, 48, $white);
                    imagefilledrectangle($im, 30, 16, 42, 22, $white);
                    imagefilledrectangle($im, 24, 28, 42, 34, $white);
                }
            ],
            'threads' => [
                'bg' => [16, 16, 16], // #101010
                'draw' => function($im, $w, $h, $white) {
                    imagesetthickness($im, 3);
                    imagearc($im, 32, 32, 32, 32, 0, 360, $white);
                    imagearc($im, 32, 32, 18, 18, 45, 315, $white);
                    imagefilledellipse($im, 32, 32, 6, 6, $white);
                }
            ],
            'tiktok' => [
                'bg' => [1, 1, 1], // #010101
                'draw' => function($im, $w, $h, $white) {
                    $cyan = imagecolorallocate($im, 37, 244, 238);
                    $red = imagecolorallocate($im, 254, 44, 85);
                    imagefilledrectangle($im, 30, 18, 36, 40, $cyan);
                    imagefilledellipse($im, 26, 40, 14, 12, $cyan);
                    imagefilledrectangle($im, 32, 16, 38, 38, $red);
                    imagefilledellipse($im, 28, 38, 14, 12, $red);
                    imagefilledrectangle($im, 31, 17, 37, 39, $white);
                    imagefilledellipse($im, 27, 39, 14, 12, $white);
                    imagefilledarc($im, 37, 23, 20, 20, 270, 360, $white, IMG_ARC_NOFILL);
                }
            ]
        ];

        foreach ($icons as $name => $cfg) {
            $im = imagecreatetruecolor(64, 64);
            imagesavealpha($im, true);
            $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
            imagefill($im, 0, 0, $transparent);

            // Circle background
            $bgColor = imagecolorallocate($im, $cfg['bg'][0], $cfg['bg'][1], $cfg['bg'][2]);
            imagefilledellipse($im, 32, 32, 60, 60, $bgColor);

            $white = imagecolorallocate($im, 255, 255, 255);
            $cfg['draw']($im, 64, 64, $white);

            foreach ($dirs as $dir) {
                imagepng($im, $dir . '/' . $name . '.png');
            }
            imagedestroy($im);
        }
    }
}
