<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function generatePrescription($patient_name, $description, $reason, $hypothesis, $date)
{
    $img_url = __DIR__ . "/pre-img.png";
    $template = imagecreatefrompng($img_url);

    $black = imagecolorallocate($template, 0, 0, 0);
    $font = __DIR__ . '/Roboto-Regular.ttf';
    $font_size = 12;

    imagettftext($template, $font_size, 0, 290, 50, $black, $font, "Patient Name: $patient_name");
    imagettftext($template, $font_size, 0, 320, 640, $black, $font, "Date: $date");
    imagettftext($template, $font_size, 0, 50, 240, $black, $font, "Diagnosis: $description");
    imagettftext($template, $font_size, 0, 50, 390, $black, $font, "Reason: $reason");
    imagettftext($template, $font_size, 0, 50, 520, $black, $font, "Hypothesis: $hypothesis");

    $output_path = __DIR__ . '/generated_prescription.png';
    $saved = imagepng($template, $output_path); // Save the image to file
    imagedestroy($template);

    return $saved ? $output_path : false;
}
