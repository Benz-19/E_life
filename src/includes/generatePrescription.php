<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$img_url = __DIR__ . "/pre-img.png";
$template = imagecreatefrompng($img_url);

$black = imagecolorallocate($template, 0, 0, 0);
$font = __DIR__ . '/Roboto-Regular.ttf';
$font_size = 12;

$patient_name = "Charles";
$description = "Has a slight headache";
$reason = "insufficient water intake";
$hypothesis = "none";
$date = "10-04-2025";

imagettftext($template, $font_size, 0, 290, 50, $black, $font, "Patient Name: $patient_name");
imagettftext($template, $font_size, 0, 320, 640, $black, $font, "Date: $date");
imagettftext($template, $font_size, 0, 50, 240, $black, $font, "Diagnosis: $description");
imagettftext($template, $font_size, 0, 50, 390, $black, $font, "Reason: $reason");
imagettftext($template, $font_size, 0, 50, 520, $black, $font, "Hypothesis: $hypothesis");

header("Content-Type: image/png");
imagepng($template);
imagedestroy($template);
exit;
