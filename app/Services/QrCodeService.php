<?php
// app/Services/QrCodeService.php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class QrCodeService
{
    /**
     * Render a QR code as a base64 PNG data-uri, ready to drop into an
     * <img src="..."> tag — same convention templates already use for
     * director.signature.
     */
    public function renderPng(string $content, int $size = 220): string
    {
        $qrCode = new QrCode(
            data: $content,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: $size,
            margin: 4,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }
}
