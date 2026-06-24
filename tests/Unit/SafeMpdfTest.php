<?php

declare(strict_types=1);

use App\Support\SafeMpdf;
use Mpdf\Mpdf;

it('strips invalid utf-8 in WriteText instead of throwing', function () {
    $invalid = "John\xE9Doe"; // lone 0xE9 byte is not valid UTF-8

    expect(fn () => (new Mpdf)->WriteText(10, 10, $invalid))
        ->toThrow(\Mpdf\MpdfException::class);

    expect(fn () => (new SafeMpdf)->WriteText(10, 10, $invalid))
        ->not->toThrow(\Mpdf\MpdfException::class);
});

it('strips invalid utf-8 in WriteCell, Write and WriteHTML', function () {
    $invalid = "Bad\xC3\x28Byte"; // invalid 2-byte sequence

    $pdf = new SafeMpdf;
    $pdf->WriteCell(0, 5, $invalid);
    $pdf->Write(5, $invalid);
    $pdf->WriteHTML("<p>{$invalid}</p>");
})->throwsNoExceptions();

it('leaves valid text unchanged', function () {
    (new SafeMpdf)->WriteText(10, 10, 'Renée Müller — café');
})->throwsNoExceptions();
