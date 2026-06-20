<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Services\Front\InquiryService;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct(private InquiryService $inquiryService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        return view('front.users.inquiries', [
            'inquiries' => $this->inquiryService->listForUser($user),
            'stats' => $this->inquiryService->statsForUser($user),
        ]);
    }

    public function show(Request $request, Inquiry $inquiry)
    {
        abort_unless($this->inquiryService->belongsToUser($inquiry, $request->user()), 404);

        return view('front.users.inquiry-view', compact('inquiry'));
    }
}
