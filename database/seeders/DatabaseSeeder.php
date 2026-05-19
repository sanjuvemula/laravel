<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Scholarship;
use App\Models\Student;
use App\Models\User;
use App\Models\Verification;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_IN');

        User::updateOrCreate(
            ['email' => 'admin@portal.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $institutionData = [
            [
                'user_email' => 'registrar.nit@portal.com',
                'user_name' => 'NIT Registrar',
                'institution_name' => 'National Institute of Technology Jaipur',
                'state' => 'Rajasthan',
                'city' => 'Jaipur',
                'registration_number' => 'RJ-NIT-1001',
                'institution_type' => 'Institute',
                'affiliated_university' => 'National Technical University',
            ],
            [
                'user_email' => 'office.pune@portal.com',
                'user_name' => 'Pune College Office',
                'institution_name' => 'Pune College of Engineering',
                'state' => 'Maharashtra',
                'city' => 'Pune',
                'registration_number' => 'MH-PCE-2045',
                'institution_type' => 'College',
                'affiliated_university' => 'Savitribai Phule Pune University',
            ],
            [
                'user_email' => 'admin.bangalore@portal.com',
                'user_name' => 'Bangalore University Admin',
                'institution_name' => 'Bangalore State University',
                'state' => 'Karnataka',
                'city' => 'Bengaluru',
                'registration_number' => 'KA-BSU-3090',
                'institution_type' => 'University',
                'affiliated_university' => null,
            ],
        ];

        $institutions = collect($institutionData)->map(function (array $data) use ($faker) {
            $user = User::updateOrCreate(
                ['email' => $data['user_email']],
                [
                    'name' => $data['user_name'],
                    'password' => Hash::make('password123'),
                    'role' => 'institution',
                ]
            );

            return Institution::updateOrCreate(
                ['registration_number' => $data['registration_number']],
                [
                    'user_id' => $user->id,
                    'institution_name' => $data['institution_name'],
                    'state' => $data['state'],
                    'city' => $data['city'],
                    'address' => $faker->streetAddress() . ', ' . $data['city'],
                    'institution_type' => $data['institution_type'],
                    'affiliated_university' => $data['affiliated_university'],
                    'contact_email' => $data['user_email'],
                    'contact_phone' => $faker->numerify('9#########'),
                    'status' => 'approved',
                ]
            );
        })->values();

        $studentData = [
            ['name' => 'Aarav Sharma', 'email' => 'aarav.student@portal.com', 'enrollment' => 'ENR2026001', 'home_state' => 'Punjab', 'institution' => 0, 'course' => 'B.Tech Computer Science', 'year' => '2nd'],
            ['name' => 'Meera Iyer', 'email' => 'meera.student@portal.com', 'enrollment' => 'ENR2026002', 'home_state' => 'Kerala', 'institution' => 1, 'course' => 'B.Com', 'year' => '1st'],
            ['name' => 'Kabir Khan', 'email' => 'kabir.student@portal.com', 'enrollment' => 'ENR2026003', 'home_state' => 'Uttar Pradesh', 'institution' => 2, 'course' => 'B.Sc Mathematics', 'year' => '3rd'],
            ['name' => 'Ananya Das', 'email' => 'ananya.student@portal.com', 'enrollment' => 'ENR2026004', 'home_state' => 'West Bengal', 'institution' => 0, 'course' => 'BBA', 'year' => '2nd'],
            ['name' => 'Rohan Patel', 'email' => 'rohan.student@portal.com', 'enrollment' => 'ENR2026005', 'home_state' => 'Gujarat', 'institution' => 1, 'course' => 'B.Tech Mechanical', 'year' => '4th'],
        ];

        $students = collect($studentData)->map(function (array $data) use ($faker, $institutions) {
            $institution = $institutions[$data['institution']];

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'student',
                ]
            );

            return Student::updateOrCreate(
                ['enrollment_number' => $data['enrollment']],
                [
                    'user_id' => $user->id,
                    'home_state' => $data['home_state'],
                    'studying_state' => $institution->state,
                    'institution_id' => $institution->id,
                    'course' => $data['course'],
                    'year' => $data['year'],
                    'phone' => $faker->numerify('8#########'),
                ]
            );
        })->values();

        $applicationData = [
            ['student' => 0, 'name' => 'Merit', 'amount' => 25000, 'verification' => 'verified', 'scholarship' => 'approved', 'remarks' => 'Enrollment and academic records verified.'],
            ['student' => 1, 'name' => 'Sports', 'amount' => 18000, 'verification' => 'pending', 'scholarship' => 'pending', 'remarks' => null],
            ['student' => 2, 'name' => 'Minority', 'amount' => 22000, 'verification' => 'rejected', 'scholarship' => 'rejected', 'remarks' => 'Enrollment details require correction.'],
        ];

        foreach ($applicationData as $data) {
            $student = $students[$data['student']];

            $scholarship = Scholarship::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'scholarship_name' => $data['name'],
                ],
                [
                    'amount' => $data['amount'],
                    'status' => $data['scholarship'],
                    'remarks' => $data['remarks'],
                    'document_path' => null,
                ]
            );

            Verification::updateOrCreate(
                ['scholarship_id' => $scholarship->id],
                [
                    'institution_id' => $student->institution_id,
                    'status' => $data['verification'],
                    'remarks' => $data['remarks'],
                    'verified_at' => $data['verification'] === 'verified' ? now() : null,
                ]
            );
        }
    }
}
