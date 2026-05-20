<?php

namespace App\Http\Controllers\Enquiry;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;   // 👈 IMPORTANT
use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\FollowUp;
use App\Models\Employee\Employee;
use App\Models\FeePayment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total enquiries count
        $totalEnquiries = Enquiry::count();

        // Recent enquiries (latest 5)
        $recentEnquiries = Enquiry::latest()->take(5)->get();

        // Pending Follow-Up Enquiries Base Query
        // (accounts for variations of follow up status strings the system might have used)
        $followUpQuery = Enquiry::whereIn('status', ['follow_up', 'followup', 'follow-up']);

        // 1. Pending Follow-up Count
        $pendingFollowUpsCount = (clone $followUpQuery)->count();

        // 2. Fetch and sort pending follow-ups
        // Sort by youngest created_at or we could join FollowUps table, 
        // using simple latest() as requested: "nearest follow-up date first OR latest created"
        $upcomingFollowUpsData = $followUpQuery->with('followUps')
            ->latest()
            ->get()
            ->map(function ($enquiry) {
                // Get the most recent follow-up date if the relation exists
                $latestFollowUp = $enquiry->followUps->sortByDesc('created_at')->first();
                $dateStr = $latestFollowUp && !empty($latestFollowUp->next_follow_up_date) 
                    ? \Carbon\Carbon::parse($latestFollowUp->next_follow_up_date)->format('d M Y, h:i A') 
                    : $enquiry->created_at->format('d M Y');

                return [
                    'id' => $enquiry->id,
                    'name' => ucfirst(strtolower($enquiry->first_name)) . ' ' . ucfirst(strtolower($enquiry->surname)),
                    'parent' => ucfirst(strtolower($enquiry->middle_name)) . ' ' . ucfirst(strtolower($enquiry->surname)),
                    'contact' => $enquiry->parent_mobile,
                    'class' => $enquiry->class,
                    'status' => $enquiry->status,
                    'date' => $dateStr,
                    // Keeping compatibility with existing dummy structure locally
                    'time' => $dateStr,
                    'type' => 'Follow-up',
                    'raw_date' => $latestFollowUp->next_follow_up_date ?? clone $enquiry->created_at 
                ];
            })
            // Sort by nearest upcoming date first
            ->sortBy('raw_date')
            ->values()
            ->all();

        // 3. Confirmed Admissions
        $confirmedAdmissionsData = Enquiry::where('status', 'confirmed')->latest()->take(5)->get();
        $confirmedAdmissionsCount = Enquiry::where('status', 'confirmed')->count();

        // 4. Total Students
        $studentsData = Admission::with('enquiry')->latest()->take(5)->get();
        $totalStudentsCount = Admission::count();

        // 5. Total Employees
        $employeesData = Employee::latest()->take(5)->get();
        $totalEmployeesCount = Employee::count();

        // 6. Dynamic Fee Calculations
        $confirmedEnquiries = Enquiry::with('admission')->whereIn('status', ['confirmed', 'CONFIRMED'])->get();
        
        $feeCollectionThisMonth = $confirmedEnquiries->sum(function($enquiry) {
            return $enquiry->admission ? ($enquiry->admission->paid_amount ?? 0) : 0;
        });
            
        $pendingFees = $confirmedEnquiries->sum(function($enquiry) {
            if ($enquiry->admission) {
                $total = $enquiry->admission->final_fees ?? $enquiry->admission->total_fee ?? $enquiry->final_fees ?? 0;
                return max(0, $total - ($enquiry->admission->paid_amount ?? 0));
            }
            return 0;
        });

        return view(
            'enquiry.dashboard',
            compact(
                'totalEnquiries', 
                'recentEnquiries', 
                'pendingFollowUpsCount', 
                'upcomingFollowUpsData',
                'confirmedAdmissionsData',
                'confirmedAdmissionsCount',
                'studentsData',
                'totalStudentsCount',
                'employeesData',
                'totalEmployeesCount',
                'feeCollectionThisMonth',
                'pendingFees'
            )
        );
    }
    
    /**
     * AJAX endpoint for Confirmed students who paid fees.
     */
    public function getConfirmedFeePaidStudents(Request $request)
    {
        try {
            $enquiries = Enquiry::with('admission')
                ->whereIn('status', ['confirmed', 'CONFIRMED'])
                ->get();
                
            $students = $enquiries->filter(function($enquiry) {
                return $enquiry->admission && $enquiry->admission->paid_amount > 0;
            })->map(function($enquiry) {
                $name = trim(($enquiry->first_name ?? '') . ' ' . ($enquiry->surname ?? ''));
                $paid = $enquiry->admission->paid_amount ?? 0;
                $total = $enquiry->admission->final_fees ?? $enquiry->admission->total_fee ?? $enquiry->final_fees ?? 0;
                
                return [
                    'name' => $name ?: 'Unknown',
                    'class' => $enquiry->class ?? 'N/A',
                    'paid_amount' => $paid,
                    'pending_amount' => max(0, $total - $paid),
                    'status' => 'Paid'
                ];
            })->values();
            
            return response()->json($students);
        } catch (\Exception $e) {
            \Log::error('Error fetching paid students: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * AJAX endpoint for Confirmed students with pending fees.
     */
    public function getConfirmedPendingFeeStudents(Request $request)
    {
        try {
            $enquiries = Enquiry::with('admission')
                ->whereIn('status', ['confirmed', 'CONFIRMED'])
                ->get();
                
            $students = $enquiries->filter(function($enquiry) {
                $total = $enquiry->admission->final_fees ?? $enquiry->admission->total_fee ?? $enquiry->final_fees ?? 0;
                $paid = $enquiry->admission->paid_amount ?? 0;
                return $enquiry->admission && ($total - $paid) > 0;
            })->map(function($enquiry) {
                $name = trim(($enquiry->first_name ?? '') . ' ' . ($enquiry->surname ?? ''));
                $total = $enquiry->admission->final_fees ?? $enquiry->admission->total_fee ?? $enquiry->final_fees ?? 0;
                $paid = $enquiry->admission->paid_amount ?? 0;
                
                return [
                    'name' => $name ?: 'Unknown',
                    'class' => $enquiry->class ?? 'N/A',
                    'paid_amount' => $paid,
                    'pending_amount' => max(0, $total - $paid),
                    'status' => 'Pending'
                ];
            })->values();
            
            return response()->json($students);
        } catch (\Exception $e) {
            \Log::error('Error fetching pending students: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

public function reports(Request $request)
{
    $fromDate = $request->get('from_date');
    $toDate = $request->get('to_date');

    // Helper functions for date filtering
    $enquiryQuery = Enquiry::query();
    $admissionQuery = Admission::query();

    if ($fromDate) {
        $enquiryQuery->whereDate('created_at', '>=', $fromDate);
        $admissionQuery->whereDate('admission_date', '>=', $fromDate);
    }
    if ($toDate) {
        $enquiryQuery->whereDate('created_at', '<=', $toDate);
        $admissionQuery->whereDate('admission_date', '<=', $toDate);
    }

    $totalEnquiries = (clone $enquiryQuery)->count();

    // Status-based counts with filters
    $newEnquiries = (clone $enquiryQuery)->where('status', 'new')->count();
    $followUpEnquiries = (clone $enquiryQuery)->where('status', 'follow-up')->count();
    $confirmedAdmissions = (clone $enquiryQuery)->where('status', 'confirmed')->count();
    $rejectedEnquiries = (clone $enquiryQuery)->where('status', 'rejected')->count();

    // Calculate conversion rate
    $conversionRate = $totalEnquiries > 0 
        ? round(($confirmedAdmissions / $totalEnquiries) * 100, 1) 
        : 0;

    // Source-wise data with filters
    $sourceWiseData = (clone $enquiryQuery)->selectRaw('source, COUNT(*) as count')
        ->groupBy('source')
        ->orderBy('count', 'desc')
        ->pluck('count', 'source')
        ->toArray();

    // Class-wise admissions with filters and normalization
    $rawAdmissions = (clone $admissionQuery)->get();
    $normalizedAdmissions = [];
    foreach ($rawAdmissions as $admission) {
        $classNum = $this->extractClassNumber($admission->class);
        $label = $classNum ? "Class $classNum" : $admission->class;
        
        if (!isset($normalizedAdmissions[$label])) {
            $normalizedAdmissions[$label] = 0;
        }
        $normalizedAdmissions[$label]++;
    }
    
    // Sort normalized admissions by count descending
    arsort($normalizedAdmissions);
    $classWiseAdmissions = $normalizedAdmissions;

    return view('enquiry.reports.index', compact(
        'totalEnquiries',
        'newEnquiries',
        'followUpEnquiries',
        'confirmedAdmissions',
        'rejectedEnquiries',
        'conversionRate',
        'sourceWiseData',
        'classWiseAdmissions'
    ));
}

/**
 * Helper to extract numeric part from class name.
 */
private function extractClassNumber($className)
{
    if (!$className) return null;
    if (preg_match('/(\d+)/', $className, $matches)) {
        return $matches[1];
    }
    return null;
}


}
