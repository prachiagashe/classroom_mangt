<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Admission;
use App\Models\Enquiry;
use App\Models\Employee\Employee;
use App\Models\User;
use App\Models\CallingData;
use App\Models\StudentAttendence;

class NameFormattingTest extends TestCase
{
    public function test_admission_student_name_accessor()
    {
        $admission = new Admission();
        $admission->student_name = 'suraj satish muthe';
        $admission->parent_name = 'satish ramchandra muthe';

        $this->assertEquals('Suraj Satish Muthe', $admission->student_name);
        $this->assertEquals('Satish Ramchandra Muthe', $admission->parent_name);
    }

    public function test_enquiry_name_accessors()
    {
        $enquiry = new Enquiry();
        $enquiry->first_name = 'suraj';
        $enquiry->middle_name = 'satish';
        $enquiry->surname = 'muthe';

        $this->assertEquals('Suraj', $enquiry->first_name);
        $this->assertEquals('Satish', $enquiry->middle_name);
        $this->assertEquals('Muthe', $enquiry->surname);
    }

    public function test_employee_name_accessors()
    {
        $employee = new Employee();
        $employee->first_name = 'first';
        $employee->middle_name = 'middle';
        $employee->last_name = 'last';

        $this->assertEquals('First', $employee->first_name);
        $this->assertEquals('Middle', $employee->middle_name);
        $this->assertEquals('Last', $employee->last_name);
        $this->assertEquals('First Middle Last', $employee->full_name);
    }

    public function test_user_name_accessor()
    {
        $user = new User();
        $user->name = 'john doe';

        $this->assertEquals('John Doe', $user->name);
    }

    public function test_calling_data_name_accessor()
    {
        $calling = new CallingData();
        $calling->student_name = 'jane doe';

        $this->assertEquals('Jane Doe', $calling->student_name);
    }

    public function test_student_attendance_name_accessor()
    {
        $attendance = new StudentAttendence();
        $attendance->name = 'john doe';

        $this->assertEquals('John Doe', $attendance->name);
    }
}
