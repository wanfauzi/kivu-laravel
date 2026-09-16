<?php

namespace App\Livewire\Traits;

use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\QRCode;

trait GeneratesQr
{
    private function generateQrDataUrl(string $payload): string
    {
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
            'scale' => 5,
            'imageBase64' => true,
        ]);

        return (new QRCode($options))->render($payload);
    }
}