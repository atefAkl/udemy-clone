<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CertificateController extends Controller
{
    /**
     * Download certificate
     */
    public function download(Certificate $certificate)
    {
        // Check if certificate belongs to authenticated user
        if ($certificate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to certificate');
        }

        // Increment download count
        $certificate->incrementDownloadCount();

        // Generate PDF or return file
        // This is a placeholder - implement actual PDF generation
        return response()->json([
            'message' => 'Certificate download initiated',
            'certificate' => $certificate->certificate_number
        ]);
    }

    /**
     * Share certificate
     */
    public function share(Certificate $certificate)
    {
        // Check if certificate belongs to authenticated user
        if ($certificate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to certificate');
        }

        return view('student.certificates.share', [
            'certificate' => $certificate,
            'shareUrl' => $certificate->verification_url
        ]);
    }
}
