<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\CertificatePdfService;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CertificateController extends Controller
{
    public function index(): View
    {
        $certificates = auth()->user()->certificates()->with('level')->latest()->get();

        return view('student.certificates', compact('certificates'));
    }

    public function show(Certificate $certificate): View
    {
        abort_unless(
            $certificate->user_id === auth()->id() || auth()->user()->isAdmin(),
            403
        );

        $certificate->load('user', 'level');

        return view('student.certificate', compact('certificate'));
    }

    /** PDF export of one certificate — same ownership rule as show(). */
    public function download(Certificate $certificate, CertificatePdfService $pdf): Response
    {
        abort_unless(
            $certificate->user_id === auth()->id() || auth()->user()->isAdmin(),
            403
        );

        $certificate->load('user', 'level');

        // No PDF engine on this deployment yet (vendor/ not refreshed after composer install):
        // hand the student back to the certificate page, which prints itself to PDF in the
        // browser, rather than failing the download outright.
        if (! $pdf->available()) {
            return redirect()->route('certificates.show', $certificate)->with('print', true);
        }

        return $pdf->download($certificate);
    }

    /** Public QR verification page. */
    public function verify(string $uuid): View
    {
        $certificate = Certificate::where('verify_uuid', $uuid)->with('user', 'level')->first();

        return view('public.verify', compact('certificate', 'uuid'));
    }
}
