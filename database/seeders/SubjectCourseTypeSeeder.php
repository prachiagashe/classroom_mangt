<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectCourseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing subjects to avoid duplicates
        DB::table('subjects')->delete();

        // Regular subjects for Class 5-10
        $regularSubjects = [
            ['English', 'ENG'],
            ['Hindi', 'HIN'],
            ['Marathi', 'MAR'],
            ['Mathematics', 'MATH'],
            ['Science', 'SCI'],
            ['Social Studies', 'SOC'],
            ['Information Technology', 'IT'],
            ['Physical Education', 'PE'],
        ];

        // NEET subjects for Class 11-12
        $neetSubjects = [
            ['Physics', 'PHY'],
            ['Chemistry', 'CHEM'],
            ['Biology', 'BIO'],
            ['English', 'ENG'],
            ['Hindi', 'HIN'],
        ];

        // JEE subjects for Class 11-12
        $jeeSubjects = [
            ['Physics', 'PHY'],
            ['Chemistry', 'CHEM'],
            ['Mathematics', 'MATH'],
            ['English', 'ENG'],
            ['Hindi', 'HIN'],
        ];

        // MHT-CET subjects for Class 11-12
        $mhtCetSubjects = [
            ['Physics', 'PHY'],
            ['Chemistry', 'CHEM'],
            ['Mathematics', 'MATH'],
            ['Biology', 'BIO'],
            ['English', 'ENG'],
            ['Hindi', 'HIN'],
        ];

        // Seed regular subjects for Class 5-10
        foreach (range(5, 10) as $class) {
            foreach ($regularSubjects as [$name, $code]) {
                Subject::create([
                    'name' => $name,
                    'code' => $code . '_' . $class,
                    'class_name' => (string)$class,
                    'course_type' => 'REGULAR',
                    'description' => $name . ' for Class ' . $class,
                    'teacher_name' => 'Teacher ' . $name,
                    'teacher_email' => strtolower($name) . $class . '@school.com',
                    'credits' => 4,
                    'is_active' => true,
                    'color' => $this->getSubjectColor($name),
                ]);
            }
        }

        // Seed NEET subjects for Class 11-12
        foreach (range(11, 12) as $class) {
            foreach ($neetSubjects as [$name, $code]) {
                Subject::create([
                    'name' => $name,
                    'code' => $code . '_NEET_' . $class,
                    'class_name' => (string)$class,
                    'course_type' => 'NEET',
                    'description' => $name . ' for NEET - Class ' . $class,
                    'teacher_name' => 'NEET Teacher ' . $name,
                    'teacher_email' => strtolower($name) . '_neet' . $class . '@school.com',
                    'credits' => 5,
                    'is_active' => true,
                    'color' => $this->getSubjectColor($name),
                ]);
            }
        }

        // Seed JEE subjects for Class 11-12
        foreach (range(11, 12) as $class) {
            foreach ($jeeSubjects as [$name, $code]) {
                Subject::create([
                    'name' => $name,
                    'code' => $code . '_JEE_' . $class,
                    'class_name' => (string)$class,
                    'course_type' => 'JEE',
                    'description' => $name . ' for JEE - Class ' . $class,
                    'teacher_name' => 'JEE Teacher ' . $name,
                    'teacher_email' => strtolower($name) . '_jee' . $class . '@school.com',
                    'credits' => 5,
                    'is_active' => true,
                    'color' => $this->getSubjectColor($name),
                ]);
            }
        }

        // Seed MHT-CET subjects for Class 11-12
        foreach (range(11, 12) as $class) {
            foreach ($mhtCetSubjects as [$name, $code]) {
                Subject::create([
                    'name' => $name,
                    'code' => $code . '_MHTCET_' . $class,
                    'class_name' => (string)$class,
                    'course_type' => 'MHT-CET',
                    'description' => $name . ' for MHT-CET - Class ' . $class,
                    'teacher_name' => 'MHT-CET Teacher ' . $name,
                    'teacher_email' => strtolower($name) . '_mhtcet' . $class . '@school.com',
                    'credits' => 5,
                    'is_active' => true,
                    'color' => $this->getSubjectColor($name),
                ]);
            }
        }
    }

    /**
     * Get color for subject based on name.
     */
    private function getSubjectColor($subjectName): string
    {
        $colors = [
            'English' => '#3B82F6',
            'Hindi' => '#F97316',
            'Marathi' => '#EC4899',
            'Mathematics' => '#10B981',
            'Science' => '#8B5CF6',
            'Social Studies' => '#F59E0B',
            'Information Technology' => '#06B6D4',
            'Physical Education' => '#84CC16',
            'Physics' => '#EF4444',
            'Chemistry' => '#6366F1',
            'Biology' => '#14B8A6',
        ];

        return $colors[$subjectName] ?? '#6B7280';
    }
}
