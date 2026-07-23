<?php
// app/Services/PdfTemplateImportService.php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use Illuminate\Http\UploadedFile;

class PdfTemplateImportService
{
    /**
     * Process uploaded file - PRESERVE ORIGINAL CONTENT
     */
    public function processFile(UploadedFile $file, string $pageSize = 'A4'): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $content = file_get_contents($file->getRealPath());

        // Store source file as base64 in database (keep original)
        $sourceFileContent = base64_encode($content);

        // Create a SIMPLE pdfme template that uses the PDF as background
        $pdfmeTemplate = $this->createPdfmeWrapper($file, $pageSize);

        return [
            'source_file_content' => $sourceFileContent,
            'source_file_type' => $extension,
            'pdfme_template' => $pdfmeTemplate,
        ];
    }

    /**
     * Create a pdfme template that references the original PDF
     * This doesn't modify the PDF content, just wraps it for pdfme
     */
    private function createPdfmeWrapper(UploadedFile $file, string $pageSize): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $dimensions = $this->getPageDimensions($pageSize);

        if ($extension === 'pdf') {
            // For PDF: Use the original PDF as basePdf (base64 string)
            // pdfme will use this as the background/image
            $pdfBase64 = base64_encode(file_get_contents($file->getRealPath()));

            return [
                'basePdf' => $pdfBase64,  // Keep original PDF as base64
                'schemas' => [[
                    [
                        'name' => 'overlay_text',
                        'type' => 'text',
                        'position' => ['x' => 50, 'y' => 50],
                        'width' => $dimensions['width'] - 100,
                        'height' => 50,
                        'fontSize' => 12,
                        'readOnly' => false,
                        'content' => 'Add text overlay here',
                        'placeholder' => 'Type to add text on PDF'
                    ]
                ]],
            ];

        } elseif ($extension === 'docx') {
            // For DOCX: Extract content but preserve original
            try {
                $phpWord = WordIOFactory::load($file->getRealPath());
                $text = '';

                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }

                return [
                    'basePdf' => [
                        'width' => $dimensions['width'],
                        'height' => $dimensions['height'],
                        'padding' => [40, 40, 40, 40],
                    ],
                    'schemas' => [[
                        [
                            'name' => 'document_content',
                            'type' => 'text',
                            'position' => ['x' => 40, 'y' => 40],
                            'width' => $dimensions['width'] - 80,
                            'height' => $dimensions['height'] - 80,
                            'fontSize' => 11,
                            'readOnly' => false,
                            'content' => $text,
                            'multiLine' => true
                        ]
                    ]],
                ];
            } catch (\Exception $e) {
                // If can't parse, create blank template
                return [
                    'basePdf' => [
                        'width' => $dimensions['width'],
                        'height' => $dimensions['height'],
                        'padding' => [40, 40, 40, 40],
                    ],
                    'schemas' => [[
                        [
                            'name' => 'content',
                            'type' => 'text',
                            'position' => ['x' => 40, 'y' => 40],
                            'width' => $dimensions['width'] - 80,
                            'height' => $dimensions['height'] - 80,
                            'fontSize' => 11,
                            'readOnly' => false,
                            'content' => 'Word document content will be preserved when you download the original',
                            'multiLine' => true
                        ]
                    ]],
                ];
            }
        }

        throw new \Exception('Unsupported file type');
    }

    /**
     * Get page dimensions in mm for pdfme
     */
    private function getPageDimensions(string $pageSize): array
    {
        $dimensions = [
            'A4' => ['width' => 210, 'height' => 297],
            'Letter' => ['width' => 215.9, 'height' => 279.4],
            'Legal' => ['width' => 215.9, 'height' => 355.6],
        ];

        return $dimensions[$pageSize] ?? $dimensions['A4'];
    }
}
