<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\View\View;

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

    /** Public QR verification page. */
    public function verify(string $uuid): View
    {
        $certificate = Certificate::where('verify_uuid', $uuid)->with('user', 'level')->first();

        return view('public.verify', compact('certificate', 'uuid'));
    }
}
