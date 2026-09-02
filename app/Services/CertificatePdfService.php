<?php

namespace App\Services;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\Response;

/**
 * Renders the certificate as a downloadable PDF.
 *
 * dompdf is a pure-PHP renderer (no FFmpeg, no exec, no headless browser), which is what
 * Hostinger shared hosting can actually run. It has two limits this class works around:
 *
 *  1. It applies no bidirectional algorithm and no Arabic contextual shaping, so Arabic
 *     arrives as disconnected letters in reversed order. Every Arabic string is therefore
 *     shaped and reordered here (see shape()) before it reaches the Blade template.
 *  2. Its SVG support is partial, so the seal and the QR are handed over as image data URIs.
 */
class CertificatePdfService
{
    /**
     * Arabic letter => its Unicode Presentation-Forms-B codepoints, ordered
     * [isolated, final, initial, medial]. A two-entry letter is right-joining:
     * it never connects to the letter that follows it.
     */
    private const FORMS = [
        0x0621 => [0xFE80],
        0x0622 => [0xFE81, 0xFE82],
        0x0623 => [0xFE83, 0xFE84],
        0x0624 => [0xFE85, 0xFE86],
        0x0625 => [0xFE87, 0xFE88],
        0x0626 => [0xFE89, 0xFE8A, 0xFE8B, 0xFE8C],
        0x0627 => [0xFE8D, 0xFE8E],
        0x0628 => [0xFE8F, 0xFE90, 0xFE91, 0xFE92],
        0x0629 => [0xFE93, 0xFE94],
        0x062A => [0xFE95, 0xFE96, 0xFE97, 0xFE98],
        0x062B => [0xFE99, 0xFE9A, 0xFE9B, 0xFE9C],
        0x062C => [0xFE9D, 0xFE9E, 0xFE9F, 0xFEA0],
        0x062D => [0xFEA1, 0xFEA2, 0xFEA3, 0xFEA4],
        0x062E => [0xFEA5, 0xFEA6, 0xFEA7, 0xFEA8],
        0x062F => [0xFEA9, 0xFEAA],
        0x0630 => [0xFEAB, 0xFEAC],
        0x0631 => [0xFEAD, 0xFEAE],
        0x0632 => [0xFEAF, 0xFEB0],
        0x0633 => [0xFEB1, 0xFEB2, 0xFEB3, 0xFEB4],
        0x0634 => [0xFEB5, 0xFEB6, 0xFEB7, 0xFEB8],
        0x0635 => [0xFEB9, 0xFEBA, 0xFEBB, 0xFEBC],
        0x0636 => [0xFEBD, 0xFEBE, 0xFEBF, 0xFEC0],
        0x0637 => [0xFEC1, 0xFEC2, 0xFEC3, 0xFEC4],
        0x0638 => [0xFEC5, 0xFEC6, 0xFEC7, 0xFEC8],
        0x0639 => [0xFEC9, 0xFECA, 0xFECB, 0xFECC],
        0x063A => [0xFECD, 0xFECE, 0xFECF, 0xFED0],
        0x0641 => [0xFED1, 0xFED2, 0xFED3, 0xFED4],
        0x0642 => [0xFED5, 0xFED6, 0xFED7, 0xFED8],
        0x0643 => [0xFED9, 0xFEDA, 0xFEDB, 0xFEDC],
        0x0644 => [0xFEDD, 0xFEDE, 0xFEDF, 0xFEE0],
        0x0645 => [0xFEE1, 0xFEE2, 0xFEE3, 0xFEE4],
        0x0646 => [0xFEE5, 0xFEE6, 0xFEE7, 0xFEE8],
        0x0647 => [0xFEE9, 0xFEEA, 0xFEEB, 0xFEEC],
        0x0648 => [0xFEED, 0xFEEE],
        0x0649 => [0xFEEF, 0xFEF0],
        0x064A => [0xFEF1, 0xFEF2, 0xFEF3, 0xFEF4],
    ];

    /** lam + alef collapse into a single ligature glyph: alef => [isolated, final]. */
    private const LAM_ALEF = [
        0x0622 => [0xFEF5, 0xFEF6],
        0x0623 => [0xFEF7, 0xFEF8],
        0x0625 => [0xFEF9, 0xFEFA],
        0x0627 => [0xFEFB, 0xFEFC],
    ];

    /** The fixed Arabic copy on the sheet, keyed the way the template reads it. */
    private const COPY = [
        'institute' => 'مؤسسة ريستراك للتدريب',
        'certificate' => 'شهادة إكمال',
        'certify_that' => 'نشهد بأن',
        'completed' => 'أتم بنجاح',
        'seal' => 'ختم المؤسسة',
        'director' => 'مدير التدريب',
    ];

    /** True when a PDF engine is actually installed (composer install may not have run yet). */
    public function available(): bool
    {
        return class_exists(Pdf::class);
    }

    public function filename(Certificate $certificate): string
    {
        return 'Restrack-'.$certificate->number.'.pdf';
    }

    /** Stream the certificate to the browser as a PDF download. */
    public function download(Certificate $certificate): Response
    {
        return Pdf::loadView('certificates.template', $this->data($certificate))
            ->setPaper('a4', 'landscape')
            ->download($this->filename($certificate));
    }

    /** Everything resources/views/certificates/template.blade.php needs. */
    public function data(Certificate $certificate): array
    {
        $name = (string) $certificate->user->name;
        $level = $certificate->level;

        $trackEn = $certificate->type === Certificate::TYPE_FINAL
            ? 'Research Track Programs (1) — From Beginner to Expert in Medical Research'
            : trim((string) optional($level)->name_en);

        $trackAr = $certificate->type === Certificate::TYPE_FINAL
            ? ''
            : trim((string) optional($level)->name_ar);

        $copy = [];
        foreach (self::COPY as $key => $value) {
            $copy[$key] = $this->shape($value);
        }

        return [
            'certificate' => $certificate,
            't' => $copy,
            'font' => 'DejaVu Sans',
            'name' => $this->shape($name),
            'nameIsArabic' => $this->hasArabic($name),
            'trackEn' => $trackEn,
            'trackAr' => $trackAr === '' ? null : $this->shape($trackAr),
            'issuedAt' => optional($certificate->issued_at)->format('Y / m / d'),
            'score' => $certificate->score === null
                ? null
                : rtrim(rtrim(number_format((float) $certificate->score, 2, '.', ''), '0'), '.'),
            'verifyUrl' => route('certificates.verify', $certificate->verify_uuid),
            'qr' => $this->qrDataUri(route('certificates.verify', $certificate->verify_uuid)),
            'seal' => $this->sealDataUri(),
            'signature' => $this->signatureDataUri(),
        ];
    }

    /**
     * Turn logical-order Arabic into the visual order dompdf will draw verbatim:
     * strip the diacritics dompdf cannot position, pick each letter's joined form,
     * then reverse — words first, then the letters inside every Arabic word.
     * Latin words and numbers keep their own left-to-right order.
     */
    public function shape(string $text): string
    {
        if (! $this->hasArabic($text)) {
            return $text;
        }

        // Harakat and tatweel: dompdf cannot place combining marks, and they carry no meaning here.
        $text = preg_replace('/[\x{064B}-\x{065F}\x{0640}\x{0670}]/u', '', $text) ?? $text;

        $words = preg_split('/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) ?: [];

        $out = [];
        foreach ($words as $word) {
            $out[] = $this->hasArabic($word) ? $this->shapeWord($word) : $word;
        }

        return implode('', array_reverse($out));
    }

    private function hasArabic(string $text): bool
    {
        return (bool) preg_match('/[\x{0600}-\x{06FF}]/u', $text);
    }

    /** Shape one word and return its letters already in visual (reversed) order. */
    private function shapeWord(string $word): string
    {
        $chars = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        // Collapse every lam+alef pair into the single cell Unicode gives it.
        $cells = [];
        for ($i = 0, $n = count($chars); $i < $n; $i++) {
            $cp = $this->codepoint($chars[$i]);
            $next = isset($chars[$i + 1]) ? $this->codepoint($chars[$i + 1]) : null;

            if ($cp === 0x0644 && $next !== null && isset(self::LAM_ALEF[$next])) {
                $cells[] = ['cp' => $cp, 'ligature' => self::LAM_ALEF[$next], 'raw' => $chars[$i].$chars[$i + 1]];
                $i++;

                continue;
            }

            $cells[] = ['cp' => $cp, 'ligature' => null, 'raw' => $chars[$i]];
        }

        $shaped = [];
        foreach ($cells as $index => $cell) {
            $cp = $cell['cp'];
            $forms = self::FORMS[$cp] ?? null;

            if ($forms === null && $cell['ligature'] === null) {
                $shaped[] = $cell['raw']; // not an Arabic letter — a digit, a comma, «…»

                continue;
            }

            // A letter joins backwards only when the cell before it connects forward — a
            // lam+alef ligature never does, because the alef closes it.
            $joinsPrev = $this->connectsForward($cells[$index - 1] ?? null);
            // …and forwards only when it is itself dual-joining and another letter follows.
            $joinsNext = $this->connectsForward($cell)
                && isset(self::FORMS[$cells[$index + 1]['cp'] ?? -1]);

            if ($cell['ligature'] !== null) {
                // The ligature has an isolated and a final form only.
                $shaped[] = $this->char($cell['ligature'][$joinsPrev ? 1 : 0]);

                continue;
            }

            $formIndex = match (true) {
                $joinsPrev && $joinsNext => 3,
                $joinsPrev => 1,
                $joinsNext => 2,
                default => 0,
            };

            $shaped[] = $this->char($forms[$formIndex] ?? $forms[0]);
        }

        return implode('', array_reverse($shaped));
    }

    /** Does this cell connect to the letter on its left? Only dual-joining letters do. */
    private function connectsForward(?array $cell): bool
    {
        return $cell !== null
            && $cell['ligature'] === null
            && count(self::FORMS[$cell['cp']] ?? []) === 4;
    }

    private function codepoint(string $char): int
    {
        return (int) mb_ord($char, 'UTF-8');
    }

    private function char(int $codepoint): string
    {
        return (string) mb_chr($codepoint, 'UTF-8');
    }

    /** The gold rosette seal — flat fills only, php-svg-lib does not do gradients reliably. */
    private function sealDataUri(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 122" width="120" height="122">'
            .'<path d="M50 88 L40 118 L54 110 L60 118 L60 88 Z" fill="#B08427"/>'
            .'<path d="M70 88 L80 118 L66 110 L60 118 L60 88 Z" fill="#8A6C1F"/>'
            .'<polygon fill="#C9A63F" points="60,12 65,18.3 71.4,13.5 74.5,20.9 82,17.9 83.1,25.9 91.1,24.9 90.1,32.9 98.1,34 95.1,41.5 102.5,44.6 97.7,51 104,56 97.7,61 102.5,67.4 95.1,70.5 98.1,78 90.1,79.1 91.1,87.1 83.1,86.1 82,94.1 74.5,91.1 71.4,98.5 65,93.7 60,100 55,93.7 48.6,98.5 45.5,91.1 38,94.1 36.9,86.1 28.9,87.1 29.9,79.1 21.9,78 24.9,70.5 17.5,67.4 22.3,61 16,56 22.3,51 17.5,44.6 24.9,41.5 21.9,34 29.9,32.9 28.9,24.9 36.9,25.9 38,17.9 45.5,20.9 48.6,13.5 55,18.3"/>'
            .'<circle cx="60" cy="56" r="34" fill="none" stroke="#FCFAF4" stroke-width="2"/>'
            .'<circle cx="60" cy="56" r="27" fill="#16264B"/>'
            .'<polygon fill="#C9A63F" points="60,30 63.4,45.5 75.3,35 68.9,49.5 84.7,48 71,56 84.7,64 68.9,62.5 75.3,77 63.4,66.5 60,82 56.6,66.5 44.7,77 51.1,62.5 35.3,64 49,56 35.3,48 51.1,49.5 44.7,35 56.6,45.5"/>'
            .'</svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    private function qrDataUri(string $url): ?string
    {
        if (! class_exists(QrCode::class)) {
            return null;
        }

        try {
            $svg = (string) QrCode::format('svg')
                ->size(240)->margin(1)->generate($url);
        } catch (\Throwable $e) {
            return null;
        }

        if (! str_contains($svg, '<svg')) {
            return null;
        }

        // simple-qrcode omits the namespace; php-svg-lib needs it on a standalone document.
        if (! str_contains($svg, 'xmlns=')) {
            $svg = preg_replace('/<svg/', '<svg xmlns="http://www.w3.org/2000/svg"', $svg, 1) ?? $svg;
        }

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    /**
     * Signature hook — the owner drops the director's transparent PNG at
     * public/images/signature.png and it renders above the ruled line. Until then the
     * ruled line plus the job title stands in for it; no signature is ever fabricated.
     */
    private function signatureDataUri(): ?string
    {
        $path = public_path('images/signature.png');

        if (! is_file($path)) {
            return null;
        }

        return 'data:image/png;base64,'.base64_encode((string) file_get_contents($path));
    }
}
