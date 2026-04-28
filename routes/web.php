<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\TeacherRegistrationController;
use App\Http\Controllers\Teacher\ScheduleController;
use App\Http\Controllers\Enquiry\DashboardController;
use App\Http\Controllers\Enquiry\EnquiryController;
use App\Http\Controllers\Enquiry\FollowUpController;
use App\Http\Controllers\Enquiry\AdmissionController;
use App\Http\Controllers\Enquiry\EnquiryFormController;
use App\Http\Controllers\Enquiry\FeesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\StudentAttendanceController;
use App\Http\Controllers\CallingController;
use App\Http\Controllers\Employee\EmployeeController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password/update', [AuthController::class, 'updatePassword'])->name('password.update');

// Teacher Registration Routes
Route::get('/register', [TeacherRegistrationController::class, 'showRegistrationForm'])->name('teacher.register');
Route::post('/register', [TeacherRegistrationController::class, 'register'])->name('teacher.register.post');


/*
|--------------------------------------------------------------------------
| Protected CRM Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('enquiry')
    ->name('enquiry.')
    ->group(function () {

        // ================= DASHBOARD =================
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/api/sessions', [DashboardController::class, 'getSessionsData'])
            ->name('api.sessions');


        // ================= ENQUIRIES =================
        Route::resource('enquiries', EnquiryController::class);

        Route::get('/enquiries/{enquiry}/details',
            [EnquiryController::class, 'details']
        )->name('enquiries.details');

        Route::post('/enquiries/{enquiry}/followup',
            [EnquiryController::class, 'followup']
        )->name('enquiries.followup');

        Route::post('/enquiries/{enquiry}/confirm',
            [EnquiryController::class, 'confirm']
        )->name('enquiries.confirm');

        Route::post('/enquiries/{enquiry}/reject',
            [EnquiryController::class, 'reject']
        )->name('enquiries.reject');

        Route::get('/enquiries/{enquiry}/print',
            [EnquiryController::class, 'print']
        )->name('enquiries.print');

        Route::get('/enquiries/{enquiry}/history',
            [EnquiryController::class, 'history']
        )->name('enquiries.history');


        // ================= FOLLOW UPS =================
        Route::get('/followups', [FollowUpController::class, 'index'])
            ->name('followups.index');

        Route::post('/followups/store',
            [FollowUpController::class, 'store']
        )->name('followups.store');

        Route::post('/followups/{followup}/accept',
            [FollowUpController::class, 'acceptFollowUp']
        )->name('followups.accept');

        Route::post('/followups/{followup}/reject',
            [FollowUpController::class, 'rejectFollowUp']
        )->name('followups.reject');

        Route::post('/followups/{followup}/confirm',
            [FollowUpController::class, 'confirmAdmission']
        )->name('followups.confirm');

        Route::post('/followups/{followup}/mark-contacted',
            [FollowUpController::class, 'markContacted']
        )->name('followups.markContacted');

        Route::post('/followups/{followup}/reschedule',
            [FollowUpController::class, 'reschedule']
        )->name('followups.reschedule');

        Route::post('/followups/{followup}/add-note',
            [FollowUpController::class, 'addNote']
        )->name('followups.addNote');


        // ================= ADMISSIONS =================
        Route::resource('admissions', AdmissionController::class);
        // PDF generation
        Route::get('/admissions/{id}/pdf', [AdmissionController::class, 'downloadPdf'])->name('admissions.pdf');

        Route::get('/admissions/{id}/track-attendence', [\App\Http\Controllers\Student\StudentAttendanceController::class, 'studentProgress'])->name('admissions.track-attendence');
        
        // Class-wise student management
        Route::get('/admissions/class/{className}/students', [AdmissionController::class, 'getClassStudents'])->name('admissions.class.students');
        Route::post('/admissions/save-attendance', [AdmissionController::class, 'saveAttendance'])->name('admissions.save.attendance');


        // ================= FEES =================
        Route::get('/fees', [FeesController::class, 'index'])
            ->name('fees');

        Route::get('/fees/{id}/show', [FeesController::class, 'show'])
            ->name('fees.show');
            
        Route::post('/{id}/fees/payment', [FeesController::class, 'storePayment'])->name('fees.payment.store');
        
        // Settings & Management
        Route::post('/{id}/fees/schedule', [FeesController::class, 'updateSchedule'])->name('fees.schedule.update');
        Route::post('/fees/payment/{paymentId}/receipt', [FeesController::class, 'sendReceipt'])->name('fees.payment.receipt');
        Route::get('/fees/payment/{paymentId}/receipt/download', [FeesController::class, 'downloadPdf'])->name('fees.payment.receipt.download');
        Route::post('/fees/{id}/reminder/whatsapp', [FeesController::class, 'whatsappReminder'])->name('fees.reminder.whatsapp');
   
   // ================= REPORTS =================
        Route::get('/reports', [DashboardController::class, 'reports'])
            ->name('reports');
        });

// Simple profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'updatePersonal'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
});

// Global Notification API routes (all authenticated users: admin, teacher, student)
Route::middleware('auth')->prefix('api/notifications')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::get('/check-new', [\App\Http\Controllers\NotificationController::class, 'checkNew'])->name('notifications.check-new');
});

// Admin student photo upload route
Route::middleware(['auth', 'role:admin'])->post('/admin/upload/student/photo', [ProfileController::class, 'uploadStudentPhoto'])->name('admin.upload.student.photo');

// Admin Attendance Management routes
Route::middleware(['auth', 'role:admin'])->prefix('admin/attendance')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::get('/class/{class}', [App\Http\Controllers\Admin\AttendanceController::class, 'showClass'])->name('admin.attendance.show_class');
    Route::post('/save', [App\Http\Controllers\Admin\AttendanceController::class, 'saveAttendance'])->name('admin.attendance.save');
    Route::get('/export', [App\Http\Controllers\Admin\AttendanceController::class, 'exportExcel'])->name('admin.attendance.export');
    Route::post('/mark', [App\Http\Controllers\Admin\AttendanceController::class, 'markAttendance'])->name('admin.attendance.mark');
});

// Admin student progress route
Route::middleware(['auth', 'role:admin'])->get('/admin/students/{id}/progress', function($id) {
    return redirect()->route('enquiry.admissions.track-attendence', $id);
})->name('admin.students.progress');

// Employee Management routes
Route::middleware('auth')->group(function () {
    Route::get('/employee', [App\Http\Controllers\Employee\EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/leave', [App\Http\Controllers\Admin\AdminLeaveController::class, 'leaveIndex'])->name('leave.index');
    Route::get('/admin/attendance/{date}', [App\Http\Controllers\Admin\AdminLeaveController::class, 'attendanceByDate'])->name('admin.attendance.date');
    Route::get('/employee/create', [App\Http\Controllers\Employee\EmployeeController::class, 'create'])->name('employee.create');
    Route::post('/employee', [App\Http\Controllers\Employee\EmployeeController::class, 'store'])->name('employee.store');
    
    // Leave Management routes (must come before parameterized routes)
    Route::get('/employee/leave', [App\Http\Controllers\Admin\AdminLeaveController::class, 'leaveIndex'])->name('employee.leave.index');
    
    Route::get('/employee/{employee}', [App\Http\Controllers\Employee\EmployeeController::class, 'show'])->name('employee.show');
    Route::get('/employee/{employee}/attendance-calendar', [App\Http\Controllers\Employee\EmployeeController::class, 'attendanceCalendar'])->name('employee.attendance-calendar');
    Route::get('/employee/{employee}/edit', [App\Http\Controllers\Employee\EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('/employee/{employee}', [App\Http\Controllers\Employee\EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/employee/{employee}', [App\Http\Controllers\Employee\EmployeeController::class, 'destroy'])->name('employee.destroy');
});

// Admin Leave Management routes
Route::middleware(['auth', 'role:admin'])->prefix('admin/leave')->name('admin.leave.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminLeaveController::class, 'index'])->name('index');
    Route::post('/mark-single', [App\Http\Controllers\Admin\AdminLeaveController::class, 'markSingleAttendance'])->name('mark-single');
    Route::post('/save-attendance', [App\Http\Controllers\Admin\AdminLeaveController::class, 'saveAttendance'])->name('save-attendance');
    Route::post('/approve/{id}', [App\Http\Controllers\Admin\AdminLeaveController::class, 'approve'])->name('approve');
    Route::post('/reject/{id}', [App\Http\Controllers\Admin\AdminLeaveController::class, 'reject'])->name('reject');
    Route::post('/bulk-approve', [App\Http\Controllers\Admin\AdminLeaveController::class, 'bulkApprove'])->name('bulk_approve');
    Route::post('/bulk-reject', [App\Http\Controllers\Admin\AdminLeaveController::class, 'bulkReject'])->name('bulk_reject');
    Route::post('/holiday', [App\Http\Controllers\Admin\AdminLeaveController::class, 'declareHoliday'])->name('holiday');
});

// Admin Subject Management routes
Route::middleware(['auth', 'role:admin'])->prefix('admin/subjects')->name('admin.subjects.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\SubjectController::class, 'classes'])->name('classes');
    Route::get('/class/{className}', [App\Http\Controllers\Admin\SubjectController::class, 'class'])->name('class');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\SubjectController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [App\Http\Controllers\Admin\SubjectController::class, 'update'])->name('update');
    Route::post('/store', [App\Http\Controllers\Admin\SubjectController::class, 'store'])->name('store');
    Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\SubjectController::class, 'destroy'])->name('destroy');
    Route::post('/save-timetable', [App\Http\Controllers\Admin\SubjectController::class, 'saveTimetable'])->name('saveTimetable');
    Route::post('/publish-timetable', [App\Http\Controllers\Admin\SubjectController::class, 'publishTimetable'])->name('publishTimetable');
    Route::post('/publish-subjects', [App\Http\Controllers\Admin\SubjectController::class, 'publishSubjects'])->name('publishSubjects');
    Route::get('/timetable/{className}', [App\Http\Controllers\Admin\SubjectController::class, 'getTimetable'])->name('getTimetable');
});

// Admin Doubt Session Management routes
Route::middleware(['auth', 'role:admin'])->prefix('admin/doubt-sessions')->name('admin.doubt-sessions.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DoubtSessionController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\DoubtSessionController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\DoubtSessionController::class, 'store'])->name('store');
    Route::get('/{doubtSession}', [App\Http\Controllers\Admin\DoubtSessionController::class, 'show'])->name('show');
    Route::get('/{doubtSession}/edit', [App\Http\Controllers\Admin\DoubtSessionController::class, 'edit'])->name('edit');
    Route::put('/{doubtSession}', [App\Http\Controllers\Admin\DoubtSessionController::class, 'update'])->name('update');
    Route::delete('/{doubtSession}', [App\Http\Controllers\Admin\DoubtSessionController::class, 'destroy'])->name('destroy');
    Route::patch('/{doubtSession}/toggle-status', [App\Http\Controllers\Admin\DoubtSessionController::class, 'toggleStatus'])->name('toggle-status');
    Route::get('/subjects/{className}', [App\Http\Controllers\Admin\DoubtSessionController::class, 'getSubjectsByClass'])->name('subjects.by-class');
});

// API Routes
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/teachers', function() {
        return App\Models\User::where('role', 'teacher')->orderBy('name')->get(['id', 'name']);
    });
});

// Teacher routes (role-based access)
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Teacher\TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Teacher\TeacherController::class, 'profile'])->name('profile');
    Route::get('/salary/history', [App\Http\Controllers\Teacher\TeacherController::class, 'salaryHistory'])->name('salary.history');
    Route::get('/leave/records', [App\Http\Controllers\Teacher\TeacherController::class, 'leaveRecords'])->name('leave.records');
    Route::get('/assignments', [App\Http\Controllers\Teacher\TeacherController::class, 'assignments'])->name('assignments');
    Route::get('/assignments/assignment', [App\Http\Controllers\Teacher\TeacherController::class, 'assignment'])->name('assignments.assignment');
    Route::get('/assignments/create', [App\Http\Controllers\Teacher\TeacherController::class, 'createAssignment'])->name('assignments.create');
    Route::post('/assignments', [App\Http\Controllers\Teacher\TeacherController::class, 'storeAssignment'])->name('assignments.store');
    Route::post('/assignments/submissions/{id}/evaluate', [App\Http\Controllers\Teacher\TeacherController::class, 'evaluateSubmission'])->name('assignments.evaluate');
    Route::get('/classes/{className}', [App\Http\Controllers\Teacher\TeacherController::class, 'classDetail'])->name('class.detail');
    Route::delete('/assignments/{id}', [App\Http\Controllers\Teacher\TeacherController::class, 'destroyAssignment'])->name('assignments.destroy');
    
    // Schedule Management routes
    Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    
    Route::post('/attendance/mark', [App\Http\Controllers\Teacher\TeacherAttendanceController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('/attendance/status', [App\Http\Controllers\Teacher\TeacherController::class, 'getTodayAttendanceStatus'])->name('attendance.status');
    
    // Leave Management routes
    Route::get('/leaves', [App\Http\Controllers\Teacher\TeacherLeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [App\Http\Controllers\Teacher\TeacherLeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [App\Http\Controllers\Teacher\TeacherLeaveController::class, 'store'])->name('leaves.store');
});

// Admin PTM Management routes
Route::middleware(['auth', 'role:admin'])->prefix('admin/ptm')->name('admin.ptm.')->group(function () {
    Route::post('/store', [App\Http\Controllers\Admin\PTMController::class, 'store'])->name('store');
    Route::put('/{id}/status', [App\Http\Controllers\Admin\PTMController::class, 'updateStatus'])->name('update.status');
    Route::delete('/{id}', [App\Http\Controllers\Admin\PTMController::class, 'destroy'])->name('destroy');
});

// Student PTM routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Course Management
    Route::get('/courses', [App\Http\Controllers\Student\StudentCourseController::class, 'index'])->name('courses');
    
    // Assignment Management
    Route::get('/assignments', [App\Http\Controllers\Student\AssignmentController::class, 'index'])->name('assignments');
    Route::post('/assignments/{id}/submit', [App\Http\Controllers\Student\AssignmentController::class, 'submit'])->name('assignments.submit');
    
    // Schedule
    Route::get('/schedule', function() {
        return view('students.schedule');
    })->name('schedule');
    
    // Timetable
    Route::get('/timetable', [StudentDashboardController::class, 'timetable'])->name('timetable');
    Route::get('/api/timetable/{className}', [StudentDashboardController::class, 'getTimetable'])->name('api.timetable');
    
    // Attendance
    Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/mark', [StudentAttendanceController::class, 'markAttendance'])->name('attendance.mark');
    
    // Grades
    Route::get('/grades', function() {
        return view('students.grades');
    })->name('grades');
    
    // Fees
    Route::get('/fees', [App\Http\Controllers\Student\FeesController::class, 'index'])->name('fees');
    Route::get('/fees/stats', [App\Http\Controllers\Student\FeesController::class, 'getFeeStats'])->name('fees.stats');

    // Profile Management
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [StudentProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/remove-photo', [StudentProfileController::class, 'removePhoto'])->name('profile.removePhoto');
    
    // PTM Meetings
    Route::get('/ptm', [App\Http\Controllers\Student\PTMController::class, 'index'])->name('student.ptm');
    Route::get('/ptm/meetings', [App\Http\Controllers\Student\PTMController::class, 'index'])->name('student.ptm.meetings'); // Legacy route
    Route::get('/ptm/join/{id}', [App\Http\Controllers\Student\PTMController::class, 'joinMeeting'])->name('student.ptm.join');
    
    // Unified Notification API routes moved to general auth group

    // Student notification API routes (kept for legacy support if needed, but header will use unified)
    Route::get('/api/notifications/ptm', [\App\Http\Controllers\Student\NotificationController::class, 'getPTMNotifications']);
    // Route::post('/api/notifications/mark-read', [\App\Http\Controllers\Student\NotificationController::class, 'markAsRead']); // Combined above
    // Route::get('/api/notifications/check-new', [\App\Http\Controllers\Student\NotificationController::class, 'checkNewNotifications']); // Combined above
    
    // API Subjects
    Route::get('/api/subjects/{className}', [StudentDashboardController::class, 'getSubjectsByClass'])->name('api.subjects');
    
    // Doubt Sessions
    Route::get('/doubt-sessions', [App\Http\Controllers\Student\DoubtSessionController::class, 'index'])->name('doubt-sessions');
    Route::get('/doubt-sessions/{doubtSession}', [App\Http\Controllers\Student\DoubtSessionController::class, 'show'])->name('doubt-sessions.show');
    Route::get('/api/doubt-sessions/upcoming', [App\Http\Controllers\Student\DoubtSessionController::class, 'getUpcomingSessions'])->name('api.doubt-sessions.upcoming');
});

// ... (rest of the code remains the same)
Route::get('/teacher/password/setup/{token}', [App\Http\Controllers\Auth\TeacherPasswordSetupController::class, 'showSetupForm'])->name('teacher.password.setup.form');
Route::post('/teacher/password/setup', [App\Http\Controllers\Auth\TeacherPasswordSetupController::class, 'setup'])->name('teacher.password.setup');

// Salary Management routes
Route::middleware('auth')->group(function () {
    Route::get('/salary', [App\Http\Controllers\Employee\SalaryController::class, 'index'])->name('salary.index');
    Route::post('/salary/generate', [App\Http\Controllers\Employee\SalaryController::class, 'generateSalary'])->name('salary.generate');
    Route::get('/salary/pay/{id}', [App\Http\Controllers\Employee\SalaryController::class, 'paySalary'])->name('salary.pay');
    Route::put('/salary/update/{id}', [App\Http\Controllers\Employee\SalaryController::class, 'updatePayment'])->name('salary.update');
    Route::get('/salary/history/{employee_code}', [App\Http\Controllers\Employee\SalaryController::class, 'history'])->name('salary.history');

    // Unified Notification API routes
    Route::prefix('api/notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index']);
        Route::post('/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);
        Route::get('/check-new', [\App\Http\Controllers\NotificationController::class, 'checkNew']);
    });
});

// Academic Assignment routes
Route::middleware('auth')->group(function () {
    Route::post('/academic-assignments', [App\Http\Controllers\Teacher\AcademicAssignmentController::class, 'store'])->name('academic.assignments.store');
    Route::get('/academic-assignments/{employee}', [App\Http\Controllers\Teacher\AcademicAssignmentController::class, 'show'])->name('academic.assignments.show');
});


/*
|--------------------------------------------------------------------------
| Admin Enquiry Form
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin/enquiry')
    ->name('admin.enquiry.')
    ->group(function () {

        Route::get('/form', [EnquiryFormController::class, 'create'])
            ->name('form');

        Route::post('/form', [EnquiryFormController::class, 'store'])
            ->name('store');

        Route::get('/{enquiry}/print',
            [EnquiryController::class, 'print']
        )->name('print');
    });


/*
|--------------------------------------------------------------------------
| Public Enquiry Form
|--------------------------------------------------------------------------
*/

Route::get('/enquiry/form',
    [EnquiryFormController::class, 'create']
)->name('enquiry.form');

Route::post('/enquiry/store',
    [EnquiryFormController::class, 'store']
)->name('enquiry.store');

// API Routes for AJAX calls
Route::middleware('auth')->group(function () {
    Route::get('/api/subjects/{className}', [StudentDashboardController::class, 'getSubjectsByClass'])->name('api.subjects.byClass');
});

/*
|--------------------------------------------------------------------------
| Calling Module Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('calling')->name('calling.')->group(function () {
        
        Route::get('/', [CallingController::class, 'index'])
            ->name('index');

        Route::get('/create', [CallingController::class, 'create'])
            ->name('create');

        Route::post('/store', [CallingController::class, 'store'])
            ->name('store');

        Route::post('/upload', [CallingController::class, 'uploadExcel'])
            ->name('upload');

        Route::get('/{id}/edit', [CallingController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [CallingController::class, 'update'])
            ->name('update');

        Route::post('/update-field/{id}', [CallingController::class, 'updateField'])
            ->name('updateField');

        Route::delete('/{id}', [CallingController::class, 'destroy'])->name('destroy');
    });

