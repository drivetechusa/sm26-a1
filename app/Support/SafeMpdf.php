<?php

namespace App\Support;

use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;

/**
 * Mpdf subclass that strips invalid UTF-8 byte sequences from text before
 * rendering. Student-entered data occasionally contains malformed bytes
 * (e.g. from copy/paste or legacy imports), which causes Mpdf to throw
 * "Text contains invalid UTF-8 character(s)". Sanitizing at every text
 * entry point prevents PDF generation from failing on bad data.
 */
class SafeMpdf extends Mpdf
{
    public function WriteText($x, $y, $txt)
    {
        parent::WriteText($x, $y, $this->sanitizeUtf8($txt));
    }

    public function Text($x, $y, $txt, $OTLdata = [], $textvar = 0, $aixextra = '', $coordsys = '', $return = false)
    {
        return parent::Text($x, $y, $this->sanitizeUtf8($txt), $OTLdata, $textvar, $aixextra, $coordsys, $return);
    }

    public function WriteCell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '', $currentx = 0)
    {
        return parent::WriteCell($w, $h, $this->sanitizeUtf8($txt), $border, $ln, $align, $fill, $link, $currentx);
    }

    public function Write($h, $txt, $currentx = 0, $link = '', $directionality = 'ltr', $align = '', $fill = 0)
    {
        return parent::Write($h, $this->sanitizeUtf8($txt), $currentx, $link, $directionality, $align, $fill);
    }

    public function WriteHTML($html, $mode = HTMLParserMode::DEFAULT_MODE, $init = true, $close = true)
    {
        return parent::WriteHTML($this->sanitizeUtf8($html), $mode, $init, $close);
    }

    private function sanitizeUtf8(?string $text): string
    {
        if ($text === null || $text === '') {
            return (string) $text;
        }

        return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
    }
}
