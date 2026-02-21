<?php

// Basic Dynamic Image Generator
// Usage: /image.php?name=Company+Name

$name = isset($_GET['name']) ? $_GET['name'] : 'Empresa';
$name = substr($name, 0, 30); // Limit length

// Path to base image
$baseImage = __DIR__ . '/img_exemplo.png';

// If no font, use built-in GD font

if (!file_exists($baseImage)) {
    die("Base image missing");
}

$im = imagecreatefrompng($baseImage);
if (!$im) {
    die("Failed to load image");
}

// Colors
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
$blue = imagecolorallocate($im, 0, 50, 150);

// If we had a TTF font we would use imagettftext
// For now, let's use built-in font for portability if TTF is missing
// But built-in fonts are ugly. Let's try to simulate a "Sign".

// Draw a white rectangle for the sign background
// Coordinates depend on the "img_exemplo.png".
// Looking at a typical facade, usually there's a space above the door.
// I'll assume a generic position: Top Center.

$imgWidth = imagesx($im);
$imgHeight = imagesy($im);

// Sign dimensions
$signW = $imgWidth * 0.6;
$signH = 60;
$signX = ($imgWidth - $signW) / 2;
$signY = $imgHeight * 0.15; // 15% from top

// Draw sign board
imagefilledrectangle($im, $signX, $signY, $signX + $signW, $signY + $signH, $white);
imagerectangle($im, $signX, $signY, $signX + $signW, $signY + $signH, $black);

// Center Text
$font = 5; // Built-in font size (1-5)
$textWidth = imagefontwidth($font) * strlen($name);
$textHeight = imagefontheight($font);

$textX = $signX + ($signW - $textWidth) / 2;
$textY = $signY + ($signH - $textHeight) / 2;

imagestring($im, $font, $textX, $textY, $name, $blue);

// Output
header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);
