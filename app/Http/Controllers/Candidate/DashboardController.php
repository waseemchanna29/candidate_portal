<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
       $candidate = Auth::user()->candidate()->with(['educations', 'experiences', 'paymentReceipt', 'course.pricingModel', 'batch'])->first();
        return view('candidate.dashboard', compact('candidate'));
    }
}