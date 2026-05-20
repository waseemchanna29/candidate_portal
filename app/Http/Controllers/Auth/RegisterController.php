<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\PaymentReceipt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Course;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('candidate.dashboard');
        }
          $courses = Course::where('is_active', true)->orderBy('name')->get();
  
        return view('auth.register',compact('courses'));
    }

    public function register(Request $request)
    {
        $request->validate([

        'course_id' => ['required', 'exists:courses,id'],
            // Account
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'confirmed', Password::min(8)],

            // Personal
            'full_name'             => ['required', 'string', 'max:255'],
            'phone'                 => ['required', 'string', 'max:20'],
            'cnic'                  => ['required', 'string', 'max:15', 'unique:candidates'],
            'address'               => ['nullable', 'string', 'max:500'],
            'city'                  => ['nullable', 'string', 'max:100'],

            // Education
            'education'             => ['required', 'array', 'min:1'],
            'education.*.degree'    => ['required', 'string'],
            'education.*.institution'   => ['required', 'string'],
            'education.*.field_of_study'=> ['required', 'string'],
            'education.*.start_year'    => ['required', 'integer', 'min:1950', 'max:' . date('Y')],
            'education.*.end_year'      => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 5)],
            'education.*.grade'         => ['nullable', 'string'],

            // Experience
            'experience'            => ['nullable', 'array'],
            'experience.*.company_name' => ['required_with:experience', 'string'],
            'experience.*.job_title'    => ['required_with:experience', 'string'],
            'experience.*.start_date'   => ['required_with:experience', 'date'],
            'experience.*.end_date'     => ['nullable', 'date'],
            'experience.*.description'  => ['nullable', 'string'],

            // Payment
            'receipt_number'        => ['required', 'string'],
            'amount'                => ['required', 'numeric', 'min:1'],
            'bank_name'             => ['required', 'string'],
            'payment_date'          => ['required', 'date'],
            'receipt_image'         => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'candidate',
            ]);

            $candidate = Candidate::create([
                 'course_id' => $request->course_id,
                'user_id'   => $user->id,
                'full_name' => $request->full_name,
                'phone'     => $request->phone,
                'cnic'      => $request->cnic,
                'address'   => $request->address,
                'city'      => $request->city,
                'status'    => 'pending',
            ]);

            foreach ($request->education as $edu) {
                Education::create([
                    'candidate_id'   => $candidate->id,
                    'degree'         => $edu['degree'],
                    'institution'    => $edu['institution'],
                    'field_of_study' => $edu['field_of_study'],
                    'start_year'     => $edu['start_year'],
                    'end_year'       => $edu['end_year'] ?? null,
                    'grade'          => $edu['grade'] ?? null,
                    'is_current'     => isset($edu['is_current']) ? 1 : 0,
                ]);
            }

            if ($request->has('experience')) {
                foreach ($request->experience as $exp) {
                    Experience::create([
                        'candidate_id' => $candidate->id,
                        'company_name' => $exp['company_name'],
                        'job_title'    => $exp['job_title'],
                        'description'  => $exp['description'] ?? null,
                        'start_date'   => $exp['start_date'],
                        'end_date'     => $exp['end_date'] ?? null,
                        'is_current'   => isset($exp['is_current']) ? 1 : 0,
                    ]);
                }
            }

            $receiptPath = $request->file('receipt_image')->store('receipts', 'public');
            PaymentReceipt::create([
                'candidate_id'   => $candidate->id,
                'receipt_number' => $request->receipt_number,
                'amount'         => $request->amount,
                'bank_name'      => $request->bank_name,
                'payment_date'   => $request->payment_date,
                'receipt_image'  => $receiptPath,
            ]);
        });

        return redirect()->route('login')->with('success', 'Registration submitted! Your application is under review. You will be notified once approved.');
    }
}