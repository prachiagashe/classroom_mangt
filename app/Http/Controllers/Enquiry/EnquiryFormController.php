<?php

namespace App\Http\Controllers\Enquiry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enquiry;

class EnquiryFormController extends Controller
{
    public function create()
    {
        $enquiryNo = str_pad(Enquiry::count() + 1, 4, '0', STR_PAD_LEFT);
        $today = date('Y-m-d');
        $isAdmin = auth()->check();

        return view('enquiry.form', [
            'enquiryNo' => $enquiryNo,
            'today' => $today,
            'noSidebar' => !$isAdmin,
            'noHeader' => !$isAdmin
        ]);
    }

    /**
     * Store enquiry data
     */
    public function store(Request $request)
    {
        // Debug: Log all request data
        \Log::info('Form submission data:', $request->all());
        
        // Dynamic validation based on auth status
        $isAdmin = auth()->check();
        
        $rules = [
            // Full Name
            'first_name'        => 'required|string|regex:/^[a-zA-Z]+$/|min:2|max:50',
            'middle_name'       => 'required|string|regex:/^[a-zA-Z]+$/|min:2|max:50',
            'surname'           => 'required|string|regex:/^[a-zA-Z]+$/|min:2|max:50',

            // Academic
            'class'             => 'required|string|max:50',
            'school_time'       => 'nullable|string|max:255',
            'last_year_percentage' => 'required|numeric|min:0|max:100',
            'school_name'       => 'required|string|max:150',
            
            // Medium
            'medium'            => 'required|in:Semi English,English,CBSE,ICSE',
            'gender'            => 'required|in:male,female,other',
            'dob'               => 'required|date|before_or_equal:today',

            // Contact
            'parent_mobile'     => 'required|regex:/^[6-9][0-9]{9}$/',
            'student_mobile'    => 'nullable|regex:/^[6-9][0-9]{9}$/',
            'whatsapp'          => 'required|regex:/^[6-9][0-9]{9}$/',
            'email'             => 'required|email:rfc,dns|max:255|unique:enquiries,email',
            'address'           => 'required|string|min:10|max:1000',

            // Sibling & Reference
            'sibling1'          => 'nullable|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'sibling2'          => 'nullable|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'reference1'        => 'nullable|string|max:100|regex:/^[A-Za-z\s]+$/',
            'reference2'        => 'nullable|string|max:100|regex:/^[A-Za-z\s]+$/',

            // Foundation & Courses
            'foundation'        => 'nullable|array',
            'course'            => 'nullable|array',

            // Source
            'source'            => 'nullable|array',

            // Remarks
            'remarks'           => 'nullable|string|in:Hot,Warm,Cold',
        ];
        
        // Add admin-only validations if user is logged in
        if ($isAdmin) {
            $rules['parent_feedback'] = 'nullable|string|max:1000';
        }
        
        $request->validate($rules, [

                    ],
        [
            // Custom error messages
            'required' => ':attribute is required',
            'digits'   => ':attribute must be 10 digits',
            'numeric'  => ':attribute must be a number',
            'array'    => ':attribute must be selected',
            'first_name.regex' => 'First Name must contain only alphabets (no spaces or numbers)',
            'middle_name.regex' => 'Middle Name must contain only alphabets (no spaces or numbers)',
            'surname.regex' => 'Surname must contain only alphabets (no spaces or numbers)',
            'first_name.min' => 'First Name must be at least 2 characters',
            'middle_name.min' => 'Middle Name must be at least 2 characters',
            'surname.min' => 'Surname must be at least 2 characters',
            'parent_mobile.regex' => 'Parent mobile must be exactly 10 digits and start with 6, 7, 8, or 9',
            'student_mobile.regex' => 'Student mobile must be exactly 10 digits and start with 6, 7, 8, or 9',
            'whatsapp.regex' => 'WhatsApp number must be exactly 10 digits and start with 6, 7, 8, or 9',
            'min'      => [
                'array' => 'At least one :attribute must be selected',
                'string' => ':attribute must be at least :min characters',
            ],
            'dob.before_or_equal' => 'Date of Birth cannot be in the future',
                        'email.email'      => 'Enter a valid email address',
            'email.unique'     => 'This email is already registered',
        ],
        [
            // Human-readable attribute names
            'first_name'        => 'First Name',
            'middle_name'       => 'Middle Name',
            'surname'           => 'Surname',
            'class'             => 'Class',
            'school_name'       => 'College/School Name',
            'last_year_percentage' => 'Last Year %',
            'medium'            => 'Medium',
            'gender'            => 'Gender',
            'dob'               => 'Date of Birth',
            'parent_mobile'     => 'Parent Mobile',
            'student_mobile'    => 'Student Mobile',
            'whatsapp'          => 'WhatsApp Number',
            'email'             => 'Email',
            'address'           => 'Address',
            'remarks'           => 'Remarks',
        ]);


        try {
            $enquiry = Enquiry::create([
                'enquiry_no' => $request->enquiry_no,
                'branch_code' => $request->branch_code,
                'date' => $request->date,

                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'surname' => $request->surname,

                'class' => $request->class,
                'school_time' => $request->school_time,
                'last_year_percentage' => $request->last_year_percentage,
                'school_name' => $request->school_name,

                'medium' => $request->medium ? (is_array($request->medium) ? implode(',', $request->medium) : $request->medium) : null,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'father_occupation' => $request->father_occupation,

                'parent_mobile' => $request->parent_mobile,
                'student_mobile' => $request->student_mobile,
                'whatsapp' => $request->whatsapp,
                'email'  => $request->email,

                'address' => $request->address,

                'foundation' => $request->foundation ? (is_array($request->foundation) ? implode(',', $request->foundation) : $request->foundation) : null,
                'course' => $request->course ? (is_array($request->course) ? implode(',', $request->course) : $request->course) : null,

                'sibling1' => $request->sibling1,
                'sibling2' => $request->sibling2,

                'reference1' => $request->reference1,
                'reference2' => $request->reference2,

                'source' => $request->source ? (is_array($request->source) ? implode(',', $request->source) : $request->source) : null,

                'remarks' => $request->remarks,
                'parent_feedback' => auth()->check() ? $request->parent_feedback : null,
            ]);

            \Log::info('Enquiry created successfully:', ['id' => $enquiry->id]);

            // WhatsApp Auto-Reply
            app(\App\Services\WhatsAppService::class)->sendMessage('new_enquiry', $enquiry);

            if (auth()->check()) {
                return redirect()->route('admin.enquiry.form')
                                 ->with('success', 'Enquiry Saved Successfully!');
            }

            return redirect()->route('login')
                             ->with('enquiry_success', 'Enquiry submitted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error saving enquiry:', ['error' => $e->getMessage()]);
            return redirect()->back()
                             ->with('error', 'Error saving enquiry: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Display all enquiries
     */
    public function index()
    {
        $enquiries = Enquiry::latest()->get();

        return view('enquiry.enquiries.index', compact('enquiries'));
    }
}
