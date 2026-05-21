<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Scholarship;
use App\Models\ScholarshipScheme;
use App\Models\SchemeTier;
use App\Models\Student;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->resetPortalTables();

        User::updateOrCreate(
            ['email' => 'admin@portal.com'],
            [
                'name' => 'Portal Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $institutions = $this->seedInstitutions();
        $schemesByInstitution = $this->seedSchemes($institutions);
        $students = $this->seedStudents($institutions);
        $this->seedApplications($students, $schemesByInstitution);
    }

    private function resetPortalTables(): void
    {
        Schema::disableForeignKeyConstraints();

        Verification::truncate();
        Scholarship::truncate();
        SchemeTier::truncate();
        ScholarshipScheme::truncate();
        Student::truncate();
        Institution::truncate();
        User::truncate();

        Schema::enableForeignKeyConstraints();
    }

    private function seedInstitutions()
    {
        $institutionData = [
            [
                'email' => 'inst1@portal.com',
                'name' => 'North Valley Registrar',
                'institution_name' => 'North Valley Institute of Technology',
                'state' => 'Punjab',
                'city' => 'Ludhiana',
                'address' => 'Sector 12 Academic Road, Ludhiana',
                'registration_number' => 'INST-PB-1001',
                'institution_type' => 'Institute',
                'affiliated_university' => 'Punjab Technical University',
                'contact_phone' => '9811111111',
            ],
            [
                'email' => 'inst2@portal.com',
                'name' => 'Western Commerce Office',
                'institution_name' => 'Western Commerce College',
                'state' => 'Maharashtra',
                'city' => 'Pune',
                'address' => '18 University Circle, Pune',
                'registration_number' => 'INST-MH-1002',
                'institution_type' => 'College',
                'affiliated_university' => 'Savitribai Phule Pune University',
                'contact_phone' => '9822222222',
            ],
            [
                'email' => 'inst3@portal.com',
                'name' => 'Southern State University Admin',
                'institution_name' => 'Southern State University',
                'state' => 'Karnataka',
                'city' => 'Bengaluru',
                'address' => '44 Knowledge Park, Bengaluru',
                'registration_number' => 'INST-KA-1003',
                'institution_type' => 'University',
                'affiliated_university' => null,
                'contact_phone' => '9833333333',
            ],
        ];

        return collect($institutionData)->map(function (array $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('inst123'),
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
                    'address' => $data['address'],
                    'institution_type' => $data['institution_type'],
                    'affiliated_university' => $data['affiliated_university'],
                    'contact_email' => $data['email'],
                    'contact_phone' => $data['contact_phone'],
                    'status' => 'approved',
                ]
            );
        })->values();
    }

    private function seedSchemes($institutions)
    {
        $schemeData = [
            [
                [
                    'scheme_name' => 'Merit Excellence Grant',
                    'description' => 'Academic merit support for students studying away from home state.',
                    'tiers' => [
                        ['tier_name' => 'Gold', 'criteria' => 'CGPA 9.0 and above', 'amount' => 50000, 'total_seats' => 20, 'deadline' => now()->addDays(60)->toDateString()],
                        ['tier_name' => 'Silver', 'criteria' => 'CGPA 8.0 to 8.99', 'amount' => 35000, 'total_seats' => 30, 'deadline' => now()->addDays(60)->toDateString()],
                        ['tier_name' => 'Bronze', 'criteria' => 'CGPA 7.0 to 7.99', 'amount' => 20000, 'total_seats' => 40, 'deadline' => now()->addDays(60)->toDateString()],
                    ],
                ],
                [
                    'scheme_name' => 'Hostel Support Scheme',
                    'description' => 'Residential support for eligible out-of-state students.',
                    'tiers' => [
                        ['tier_name' => 'Full Hostel Aid', 'criteria' => 'Annual family income below 2 lakh', 'amount' => 45000, 'total_seats' => 15, 'deadline' => now()->addDays(75)->toDateString()],
                        ['tier_name' => 'Partial Hostel Aid', 'criteria' => 'Annual family income below 4 lakh', 'amount' => 28000, 'total_seats' => 25, 'deadline' => now()->addDays(75)->toDateString()],
                        ['tier_name' => 'Travel Add-on', 'criteria' => 'Home state over 800 km away', 'amount' => 12000, 'total_seats' => 35, 'deadline' => now()->addDays(75)->toDateString()],
                    ],
                ],
            ],
            [
                [
                    'scheme_name' => 'STEM Advancement Scholarship',
                    'description' => 'Support for high-performing science and technology students.',
                    'tiers' => [
                        ['tier_name' => 'Research Track', 'criteria' => 'Published project or research work', 'amount' => 55000, 'total_seats' => 12, 'deadline' => now()->addDays(65)->toDateString()],
                        ['tier_name' => 'Innovation Track', 'criteria' => 'Prototype or patent submission', 'amount' => 42000, 'total_seats' => 18, 'deadline' => now()->addDays(65)->toDateString()],
                        ['tier_name' => 'Academic Track', 'criteria' => 'CGPA 8.0 and above', 'amount' => 25000, 'total_seats' => 30, 'deadline' => now()->addDays(65)->toDateString()],
                    ],
                ],
                [
                    'scheme_name' => 'Women in Higher Education',
                    'description' => 'Scholarship assistance for women pursuing undergraduate and postgraduate courses.',
                    'tiers' => [
                        ['tier_name' => 'Leadership Tier', 'criteria' => 'Leadership role with merit record', 'amount' => 48000, 'total_seats' => 10, 'deadline' => now()->addDays(80)->toDateString()],
                        ['tier_name' => 'Academic Tier', 'criteria' => 'CGPA 7.5 and above', 'amount' => 32000, 'total_seats' => 22, 'deadline' => now()->addDays(80)->toDateString()],
                        ['tier_name' => 'Access Tier', 'criteria' => 'First-generation learner', 'amount' => 22000, 'total_seats' => 35, 'deadline' => now()->addDays(80)->toDateString()],
                    ],
                ],
            ],
            [
                [
                    'scheme_name' => 'Research Pathway Grant',
                    'description' => 'Project funding for students entering supervised research pathways.',
                    'tiers' => [
                        ['tier_name' => 'Major Project', 'criteria' => 'Approved major research proposal', 'amount' => 60000, 'total_seats' => 8, 'deadline' => now()->addDays(70)->toDateString()],
                        ['tier_name' => 'Minor Project', 'criteria' => 'Approved departmental project', 'amount' => 30000, 'total_seats' => 16, 'deadline' => now()->addDays(70)->toDateString()],
                        ['tier_name' => 'Conference Support', 'criteria' => 'Accepted paper or presentation', 'amount' => 18000, 'total_seats' => 24, 'deadline' => now()->addDays(70)->toDateString()],
                    ],
                ],
                [
                    'scheme_name' => 'Sports Achievement Scholarship',
                    'description' => 'Financial support for students with state or national sports achievements.',
                    'tiers' => [
                        ['tier_name' => 'National Medalist', 'criteria' => 'National level medal certificate', 'amount' => 52000, 'total_seats' => 10, 'deadline' => now()->addDays(90)->toDateString()],
                        ['tier_name' => 'State Medalist', 'criteria' => 'State level medal certificate', 'amount' => 34000, 'total_seats' => 18, 'deadline' => now()->addDays(90)->toDateString()],
                        ['tier_name' => 'Participation Tier', 'criteria' => 'Recognized state or national participation', 'amount' => 18000, 'total_seats' => 28, 'deadline' => now()->addDays(90)->toDateString()],
                    ],
                ],
            ],
        ];

        return $institutions->map(function (Institution $institution, int $institutionIndex) use ($schemeData) {
            return collect($schemeData[$institutionIndex])->map(function (array $schemeInfo) use ($institution) {
                $scheme = ScholarshipScheme::updateOrCreate(
                    [
                        'institution_id' => $institution->id,
                        'scheme_name' => $schemeInfo['scheme_name'],
                    ],
                    [
                        'description' => $schemeInfo['description'],
                        'is_active' => true,
                    ]
                );

                foreach ($schemeInfo['tiers'] as $tierInfo) {
                    SchemeTier::updateOrCreate(
                        [
                            'scheme_id' => $scheme->id,
                            'tier_name' => $tierInfo['tier_name'],
                        ],
                        [
                            'criteria' => $tierInfo['criteria'],
                            'amount' => $tierInfo['amount'],
                            'total_seats' => $tierInfo['total_seats'],
                            'filled_seats' => 0,
                            'deadline' => $tierInfo['deadline'],
                        ]
                    );
                }

                return $scheme->load('tiers');
            })->values();
        })->values();
    }

    private function seedStudents($institutions)
    {
        $studentData = [
            ['name' => 'Aarav Sharma', 'email' => 'student1@portal.com', 'enrollment' => 'ENR2026001', 'home_state' => 'Rajasthan', 'institution' => 0, 'course' => 'B.Tech Computer Science', 'year' => '2nd', 'phone' => '8711111111'],
            ['name' => 'Meera Iyer', 'email' => 'student2@portal.com', 'enrollment' => 'ENR2026002', 'home_state' => 'Kerala', 'institution' => 0, 'course' => 'B.Tech Electronics', 'year' => '1st', 'phone' => '8722222222'],
            ['name' => 'Kabir Khan', 'email' => 'student3@portal.com', 'enrollment' => 'ENR2026003', 'home_state' => 'Uttar Pradesh', 'institution' => 1, 'course' => 'B.Com Honors', 'year' => '3rd', 'phone' => '8733333333'],
            ['name' => 'Ananya Das', 'email' => 'student4@portal.com', 'enrollment' => 'ENR2026004', 'home_state' => 'West Bengal', 'institution' => 2, 'course' => 'B.Sc Mathematics', 'year' => '2nd', 'phone' => '8744444444'],
            ['name' => 'Rohan Patel', 'email' => 'student5@portal.com', 'enrollment' => 'ENR2026005', 'home_state' => 'Gujarat', 'institution' => 1, 'course' => 'BBA', 'year' => '4th', 'phone' => '8755555555'],
        ];

        return collect($studentData)->map(function (array $data) use ($institutions) {
            $institution = $institutions[$data['institution']];

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('student123'),
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
                    'phone' => $data['phone'],
                ]
            );
        })->values();
    }

    private function seedApplications($students, $schemesByInstitution): void
    {
        $applicationData = [
            ['student' => 0, 'scheme' => 0, 'tier' => 'Gold', 'verification' => 'verified', 'scholarship' => 'approved', 'remarks' => 'Enrollment and merit records verified.'],
            ['student' => 1, 'scheme' => 1, 'tier' => 'Partial Hostel Aid', 'verification' => 'pending', 'scholarship' => 'pending', 'remarks' => null],
            ['student' => 2, 'scheme' => 0, 'tier' => 'Academic Track', 'verification' => 'verified', 'scholarship' => 'verified', 'remarks' => 'Course and academic eligibility verified.'],
            ['student' => 3, 'scheme' => 1, 'tier' => 'Participation Tier', 'verification' => 'rejected', 'scholarship' => 'rejected', 'remarks' => 'Sports certificate did not match the selected tier.'],
            ['student' => 4, 'scheme' => 1, 'tier' => 'Academic Tier', 'verification' => 'verified', 'scholarship' => 'approved', 'remarks' => 'Student profile and documents verified.'],
        ];

        foreach ($applicationData as $index => $data) {
            $student = $students[$data['student']];
            $institutionIndex = $student->institution_id === $students[0]->institution_id ? 0 : ($student->institution_id === $students[2]->institution_id ? 1 : 2);
            $scheme = $schemesByInstitution[$institutionIndex][$data['scheme']]->fresh('tiers');
            $tier = $scheme->tiers->firstWhere('tier_name', $data['tier']);
            $documentPath = 'documents/sample-application-' . ($index + 1) . '.pdf';

            Storage::disk('public')->put($documentPath, $this->sampleDocument($student->user->name, $scheme->scheme_name, $tier->tier_name));

            $scholarship = Scholarship::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'tier_id' => $tier->id,
                ],
                [
                    'scholarship_name' => $scheme->scheme_name,
                    'amount' => $tier->amount,
                    'status' => $data['scholarship'],
                    'remarks' => $data['remarks'],
                    'document_path' => $documentPath,
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

        SchemeTier::query()->get()->each(function (SchemeTier $tier) {
            $tier->update(['filled_seats' => $tier->scholarships()->count()]);
        });
    }

    private function sampleDocument(string $studentName, string $schemeName, string $tierName): string
    {
        return "Sample scholarship document\n"
            . "Student: {$studentName}\n"
            . "Scheme: {$schemeName}\n"
            . "Tier: {$tierName}\n"
            . "Generated: " . now()->toDateTimeString() . "\n";
    }
}
