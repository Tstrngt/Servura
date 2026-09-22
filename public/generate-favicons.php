<?php

/**
 * Generate favicon package for Servura.
 *
 * Run from project root with:
 *   php public/generate-favicons.php
 */

$source = __DIR__ . '/favicon-32x32.png';
$svgSource = __DIR__ . '/favicon.svg';

if (!extension_loaded('gd')) {
    fwrite(STDERR, "GD extension is not loaded.\n");
    exit(1);
}

$baseSize = 32;

// Try to use existing 32x32 PNG as base, otherwise render from scratch.
if (file_exists($source)) {
    $base = imagecreatefrompng($source);
    imagealphablending($base, false);
    imagesavealpha($base, true);
} else {
    $base = renderServuraIcon(32);
    imagepng($base, $source);
}

// 16x16 downscale from base
$size16 = resizeImage($base, 16, 16);
imagepng($size16, __DIR__ . '/favicon-16x16.png');

// 32x32 copy (base is already 32x32)
imagepng($base, __DIR__ . '/favicon-32x32.png');

// 48x48 from base
$size48 = resizeImage($base, 48, 48);
imagepng($size48, __DIR__ . '/favicon-48x48.png');

// Apple touch icon (180x180) - render high-res from scratch
$touch = renderServuraIcon(180);
imagepng($touch, __DIR__ . '/apple-touch-icon.png');

// Multi-resolution ICO with 16x16, 32x32 and 48x48 PNGs
$icoPath = __DIR__ . '/favicon.ico';
$icoImages = [
    ['data' => imageToPng($size16), 'width' => 16, 'height' => 16],
    ['data' => imageToPng($base), 'width' => 32, 'height' => 32],
    ['data' => imageToPng($size48), 'width' => 48, 'height' => 48],
];
file_put_contents($icoPath, buildIco($icoImages));

echo "Favicons generated successfully.\n";

function imageToPng($image): string
{
    ob_start();
    imagepng($image);
    return ob_get_clean();
}

function resizeImage($src, int $width, int $height)
{
    $dst = imagecreatetruecolor($width, $height);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, imagesx($src), imagesy($src));
    return $dst;
}

function renderServuraIcon(int $size)
{
    $img = imagecreatetruecolor($size, $size);
    imagealphablending($img, true);
    imagesavealpha($img, true);

    // Transparent background
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagefill($img, 0, 0, $transparent);

    $radius = $size * 0.22;

    // Dark rounded background gradient-ish (top-left to bottom-right)
    $bgTop = imagecolorallocate($img, 15, 23, 42);   // #0f172a
    $bgBottom = imagecolorallocate($img, 30, 41, 59);  // #1e293b

    $r = (int) round($radius);
    $d = (int) round($radius * 2);

    // Draw rounded rect by filling circle corners
    imagefilledrectangle($img, $r, 0, $size - $r - 1, $size - 1, $bgTop);
    imagefilledrectangle($img, 0, $r, $size - 1, $size - $r - 1, $bgTop);
    imagefilledellipse($img, $r, $r, $d, $d, $bgTop);
    imagefilledellipse($img, $size - $r - 1, $r, $d, $d, $bgTop);
    imagefilledellipse($img, $r, $size - $r - 1, $d, $d, $bgTop);
    imagefilledellipse($img, $size - $r - 1, $size - $r - 1, $d, $d, $bgTop);

    // Simple bottom-right gradient overlay
    for ($y = 0; $y < $size; $y++) {
        $ratio = $y / $size;
        $r = 15 + (30 - 15) * $ratio;
        $g = 23 + (41 - 23) * $ratio;
        $b = 42 + (59 - 42) * $ratio;
        $color = imagecolorallocate($img, (int) $r, (int) $g, (int) $b);
        imageline($img, 0, $y, $size - 1, $y, $color);
    }

    // Re-apply rounded mask to keep gradient inside rounded corners
    applyRoundedMask($img, $radius);

    // White "S"
    $white = imagecolorallocate($img, 255, 255, 255);

    $fontSize = $size * 0.58;
    $fontFile = 'C:/Windows/Fonts/arialbd.ttf';

    if (file_exists($fontFile)) {
        $bbox = imagettfbbox($fontSize, 0, $fontFile, 'S');
        $textWidth = $bbox[2] - $bbox[0];
        $textHeight = $bbox[1] - $bbox[7];
        $x = ($size - $textWidth) / 2 - ($bbox[0] < 0 ? $bbox[0] : 0);
        $y = ($size + $textHeight) / 2 - $bbox[1];
        imagettftext($img, $fontSize, 0, (int) $x, (int) $y, $white, $fontFile, 'S');
    } else {
        // Fallback: draw a blocky S using rectangles
        drawBlockyS($img, $size, $white);
    }

    // Blue dot bottom-right
    $dotSize = max(2, (int) round($size * 0.1));
    $dotX = (int) round($size * 0.78);
    $dotY = (int) round($size * 0.78);
    $blue = imagecolorallocate($img, 14, 165, 233); // #0ea5e9
    imagefilledellipse($img, $dotX, $dotY, $dotSize * 2, $dotSize * 2, $blue);

    return $img;
}

function applyRoundedMask($image, float $radius): void
{
    $w = imagesx($image);
    $h = imagesy($image);
    $r = (int) round($radius);
    $d = (int) round($radius * 2);

    // Create mask with rounded corners
    $mask = imagecreatetruecolor($w, $h);
    imagealphablending($mask, false);
    imagesavealpha($mask, true);
    $transparent = imagecolorallocatealpha($mask, 0, 0, 0, 127);
    imagefill($mask, 0, 0, $transparent);

    $black = imagecolorallocatealpha($mask, 0, 0, 0, 0);
    imagefilledrectangle($mask, $r, 0, $w - $r - 1, $h - 1, $black);
    imagefilledrectangle($mask, 0, $r, $w - 1, $h - $r - 1, $black);
    imagefilledellipse($mask, $r, $r, $d, $d, $black);
    imagefilledellipse($mask, $w - $r - 1, $r, $d, $d, $black);
    imagefilledellipse($mask, $r, $h - $r - 1, $d, $d, $black);
    imagefilledellipse($mask, $w - $r - 1, $h - $r - 1, $d, $d, $black);

    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $alpha = imagecolorat($mask, $x, $y) >> 24;
            if ($alpha > 0) {
                imagesetpixel($image, $x, $y, imagecolorallocatealpha($image, 0, 0, 0, 127));
            }
        }
    }

    imagedestroy($mask);
}

function drawBlockyS($image, int $size, int $color): void
{
    $unit = max(1, (int) round($size / 8));
    $x0 = (int) round($size * 0.28);
    $y0 = (int) round($size * 0.18);

    // Top bar
    imagefilledrectangle($image, $x0, $y0, $x0 + $unit * 4, $y0 + $unit, $color);
    // Left vertical
    imagefilledrectangle($image, $x0, $y0, $x0 + $unit, $y0 + $unit * 3, $color);
    // Middle bar
    imagefilledrectangle($image, $x0, $y0 + $unit * 2, $x0 + $unit * 4, $y0 + $unit * 3, $color);
    // Right vertical
    imagefilledrectangle($image, $x0 + $unit * 3, $y0 + $unit * 2, $x0 + $unit * 4, $y0 + $unit * 5, $color);
    // Bottom bar
    imagefilledrectangle($image, $x0, $y0 + $unit * 4, $x0 + $unit * 4, $y0 + $unit * 5, $color);
    // Left bottom vertical
    imagefilledrectangle($image, $x0, $y0 + $unit * 4, $x0 + $unit, $y0 + $unit * 6, $color);
}

function buildIco(array $images): string
{
    $count = count($images);
    $header = pack('vvv', 0, 1, $count);

    $offset = 6 + ($count * 16);
    $directory = '';
    $dataBlock = '';

    foreach ($images as $image) {
        $width = $image['width'];
        $height = $image['height'];
        $png = $image['data'];
        $size = strlen($png);

        $directory .= pack('CCCCvvVV',
            $width > 255 ? 0 : $width,
            $height > 255 ? 0 : $height,
            0,  // colors
            0,  // reserved
            1,  // color planes
            32, // bits per pixel
            $size,
            $offset
        );

        $dataBlock .= $png;
        $offset += $size;
    }

    return $header . $directory . $dataBlock;
}
