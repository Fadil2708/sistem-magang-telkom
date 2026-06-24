<?php

$base = __DIR__ . '/../public/images';
$files = [
    'TLK_BIG.png' => ['w' => 800, 'h' => 400, 'q' => 82],
    'TLK.png'     => ['w' => 300, 'h' => 300, 'q' => 85],
    'map-thumbnail.png' => ['w' => 400, 'h' => 300, 'q' => 80],
];

foreach ($files as $name => $opts) {
    $srcPath = $base . '/' . $name;
    if (!file_exists($srcPath)) {
        echo "Skip: {$name} not found\n";
        continue;
    }

    $src = imagecreatefrompng($srcPath);
    if (!$src) {
        echo "Failed to load: {$name}\n";
        continue;
    }

    $oldW = imagesx($src);
    $oldH = imagesy($src);
    $ratio = min($opts['w'] / $oldW, $opts['h'] / $oldH);
    $newW = (int) ($oldW * $ratio);
    $newH = (int) ($oldH * $ratio);

    $dst = imagecreatetruecolor($newW, $newH);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $oldW, $oldH);

    $webpName = str_replace('.png', '.webp', $name);
    $webpPath = $base . '/' . $webpName;
    imagewebp($dst, $webpPath, $opts['q']);

    $origKb = round(filesize($srcPath) / 1024, 1);
    $webpKb = round(filesize($webpPath) / 1024, 1);
    $saved = round((1 - $webpKb / $origKb) * 100);

    echo "{$name}: {$origKb}KB -> {$webpKb}KB ({$saved}% saved)\n";

    imagedestroy($src);
    imagedestroy($dst);
}

echo "\nDone. Run:  php artisan optimize:clear  to clear cache.\n";
