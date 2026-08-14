<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Employee;
use App\Models\EmployeeProfile;
use App\Models\EmployeeJoiningDetail;
use App\Models\SalaryStructure;
use App\Models\Candidate;
use App\Models\FinanceRequirement;
use App\Services\LeaveService;
use Illuminate\Support\Facades\Hash;

class EmployeeSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Leave Types
        $leaveTypes = [
            ['name' => 'Casual Leave', 'slug' => 'casual-leave', 'days_allowed_per_year' => 12, 'is_paid' => true],
            ['name' => 'Sick Leave', 'slug' => 'sick-leave', 'days_allowed_per_year' => 10, 'is_paid' => true],
            ['name' => 'Earned Leave', 'slug' => 'earned-leave', 'days_allowed_per_year' => 15, 'is_paid' => true],
            ['name' => 'Paid Leave', 'slug' => 'paid-leave', 'days_allowed_per_year' => 10, 'is_paid' => true],
            ['name' => 'Unpaid Leave', 'slug' => 'unpaid-leave', 'days_allowed_per_year' => 0, 'is_paid' => false],
            ['name' => 'Half Day', 'slug' => 'half-day', 'days_allowed_per_year' => 6, 'is_paid' => true],
        ];

        foreach ($leaveTypes as $lt) {
            LeaveType::firstOrCreate(['slug' => $lt['slug']], $lt);
        }

        // 2. Create Sample Employee 1: Rahul Sharma (Developer)
        $rahulUser = User::firstOrCreate(
            ['email' => 'rahul@example.com'],
            [
                'name' => 'Rahul Sharma',
                'mobile' => '9876543210',
                'department' => 'Software Development',
                'designation' => 'Senior Developer',
                'status' => 'active',
                'password' => Hash::make('password'),
            ]
        );

        $hrRole = Role::where('slug', 'hr')->first();
        if ($hrRole) {
            $rahulUser->roles()->sync([$hrRole->id]);
        }

        $rahulEmp = Employee::firstOrCreate(
            ['user_id' => $rahulUser->id],
            [
                'employee_code' => 'EMP-1001',
            ]
        );

        EmployeeProfile::firstOrCreate(['employee_id' => $rahulEmp->id], [
            'gender' => 'Male',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'country' => 'India',
        ]);

        EmployeeJoiningDetail::firstOrCreate(['employee_id' => $rahulEmp->id], [
            'joining_date' => '2025-01-15',
            'employment_type' => 'Full Time',
            'employment_status' => 'Active',
            'work_location' => 'Mumbai HQ',
        ]);

        SalaryStructure::firstOrCreate(['employee_id' => $rahulEmp->id], [
            'basic_salary' => 35000,
            'hra' => 12000,
            'allowances' => 5000,
            'bonus' => 2000,
            'pf_deduction' => 2100,
            'other_deductions' => 500,
            'gross_salary' => 54000,
            'net_salary' => 51400,
            'effective_date' => '2025-01-15',
        ]);

        LeaveService::initializeBalances($rahulEmp);

        // 3. Create Sample Employee 2: Priya Patel (HR Executive)
        $priyaUser = User::firstOrCreate(
            ['email' => 'priya@example.com'],
            [
                'name' => 'Priya Patel',
                'mobile' => '9876543211',
                'department' => 'Human Resources',
                'designation' => 'HR Executive',
                'status' => 'active',
                'password' => Hash::make('password'),
            ]
        );

        if ($hrRole) {
            $priyaUser->roles()->sync([$hrRole->id]);
        }

        $priyaEmp = Employee::firstOrCreate(
            ['user_id' => $priyaUser->id],
            [
                'employee_code' => 'EMP-1002',
            ]
        );

        EmployeeProfile::firstOrCreate(['employee_id' => $priyaEmp->id], [
            'gender' => 'Female',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'country' => 'India',
        ]);

        EmployeeJoiningDetail::firstOrCreate(['employee_id' => $priyaEmp->id], [
            'joining_date' => '2025-03-01',
            'employment_type' => 'Full Time',
            'employment_status' => 'Active',
            'work_location' => 'Ahmedabad Regional Office',
        ]);

        SalaryStructure::firstOrCreate(['employee_id' => $priyaEmp->id], [
            'basic_salary' => 25000,
            'hra' => 8000,
            'allowances' => 4000,
            'bonus' => 1000,
            'pf_deduction' => 1500,
            'other_deductions' => 300,
            'gross_salary' => 38000,
            'net_salary' => 36200,
            'effective_date' => '2025-03-01',
        ]);

        LeaveService::initializeBalances($priyaEmp);

        // 4. Create Sample Finance Employee: Vikram Malhotra
        $financeRole = Role::firstOrCreate(
            ['slug' => 'finance'],
            [
                'name' => 'Finance Executive',
                'description' => 'Finance & Accounting Operations Executive',
                'status' => 'active',
            ]
        );

        $financePerms = Permission::where('module', 'Finance')->pluck('id');
        if ($financePerms->count() > 0) {
            $financeRole->permissions()->sync($financePerms);
        }

        $vikramUser = User::firstOrCreate(
            ['email' => 'vikram@example.com'],
            [
                'name' => 'Vikram Malhotra',
                'mobile' => '9898776655',
                'department' => 'Finance & Accounts',
                'designation' => 'Finance Executive',
                'status' => 'active',
                'password' => Hash::make('password'),
            ]
        );

        $vikramUser->roles()->sync([$financeRole->id]);
        $vikramUser->permissions()->syncWithoutDetaching($financePerms);

        $vikramEmp = Employee::firstOrCreate(
            ['user_id' => $vikramUser->id],
            [
                'employee_code' => 'EMP-1003',
            ]
        );

        EmployeeProfile::firstOrCreate(['employee_id' => $vikramEmp->id], [
            'gender' => 'Male',
            'city' => 'Chennai',
            'state' => 'Tamil Nadu',
            'country' => 'India',
        ]);

        EmployeeJoiningDetail::firstOrCreate(['employee_id' => $vikramEmp->id], [
            'joining_date' => '2025-02-01',
            'employment_type' => 'Full Time',
            'employment_status' => 'Active',
            'work_location' => 'Chennai Office',
        ]);

        SalaryStructure::firstOrCreate(['employee_id' => $vikramEmp->id], [
            'basic_salary' => 32000,
            'hra' => 10000,
            'allowances' => 4500,
            'bonus' => 1500,
            'pf_deduction' => 1900,
            'other_deductions' => 400,
            'gross_salary' => 48000,
            'net_salary' => 45700,
            'effective_date' => '2025-02-01',
        ]);

        LeaveService::initializeBalances($vikramEmp);

        // 5. Create BDA Employee: Arjun Kapoor
        $bdaRole = Role::firstOrCreate(
            ['slug' => 'bda'],
            [
                'name' => 'BDA',
                'description' => 'Business Development Associate for sales and client acquisition',
                'status' => 'active',
            ]
        );

        $arjunUser = User::firstOrCreate(
            ['email' => 'arjun@example.com'],
            [
                'name' => 'Arjun Kapoor',
                'mobile' => '9811223344',
                'department' => 'Business Development',
                'designation' => 'Business Development Associate (BDA)',
                'status' => 'active',
                'password' => Hash::make('password'),
            ]
        );
        $arjunUser->roles()->sync([$bdaRole->id]);
        $arjunEmp = Employee::firstOrCreate(['user_id' => $arjunUser->id], ['employee_code' => 'EMP-1004']);
        EmployeeProfile::firstOrCreate(['employee_id' => $arjunEmp->id], ['gender' => 'Male', 'city' => 'Delhi', 'state' => 'Delhi', 'country' => 'India']);
        EmployeeJoiningDetail::firstOrCreate(['employee_id' => $arjunEmp->id], ['joining_date' => '2025-04-01', 'employment_type' => 'Full Time', 'employment_status' => 'Active', 'work_location' => 'Delhi Regional Office']);
        SalaryStructure::firstOrCreate(['employee_id' => $arjunEmp->id], ['basic_salary' => 28000, 'hra' => 9000, 'allowances' => 4000, 'bonus' => 2000, 'pf_deduction' => 1600, 'other_deductions' => 400, 'gross_salary' => 43000, 'net_salary' => 41000, 'effective_date' => '2025-04-01']);
        LeaveService::initializeBalances($arjunEmp);

        // 6. Create Talent Acquisition Employee: Ananya Roy
        $taRole = Role::firstOrCreate(
            ['slug' => 'talent-acquisition'],
            [
                'name' => 'Talent Acquisition',
                'description' => 'Talent Acquisition Specialist for recruitment pipeline',
                'status' => 'active',
            ]
        );

        $ananyaUser = User::firstOrCreate(
            ['email' => 'ananya@example.com'],
            [
                'name' => 'Ananya Roy',
                'mobile' => '9744556677',
                'department' => 'Talent Acquisition & HR',
                'designation' => 'Talent Acquisition Specialist',
                'status' => 'active',
                'password' => Hash::make('password'),
            ]
        );
        $ananyaUser->roles()->sync([$taRole->id]);
        $ananyaEmp = Employee::firstOrCreate(['user_id' => $ananyaUser->id], ['employee_code' => 'EMP-1005']);
        EmployeeProfile::firstOrCreate(['employee_id' => $ananyaEmp->id], ['gender' => 'Female', 'city' => 'Kolkata', 'state' => 'West Bengal', 'country' => 'India']);
        EmployeeJoiningDetail::firstOrCreate(['employee_id' => $ananyaEmp->id], ['joining_date' => '2025-03-15', 'employment_type' => 'Full Time', 'employment_status' => 'Active', 'work_location' => 'Kolkata Hub']);
        SalaryStructure::firstOrCreate(['employee_id' => $ananyaEmp->id], ['basic_salary' => 30000, 'hra' => 9500, 'allowances' => 4200, 'bonus' => 1500, 'pf_deduction' => 1800, 'other_deductions' => 400, 'gross_salary' => 45200, 'net_salary' => 43000, 'effective_date' => '2025-03-15']);
        LeaveService::initializeBalances($ananyaEmp);

        // 7. Create Data Entry Employee: Suresh Kumar
        $dataEntryRole = Role::firstOrCreate(
            ['slug' => 'data-entry'],
            [
                'name' => 'Data Entry',
                'description' => 'Data entry operator for records creation',
                'status' => 'active',
            ]
        );

        $sureshUser = User::firstOrCreate(
            ['email' => 'suresh@example.com'],
            [
                'name' => 'Suresh Kumar',
                'mobile' => '9633221100',
                'department' => 'Operations',
                'designation' => 'Data Entry Operator',
                'status' => 'active',
                'password' => Hash::make('password'),
            ]
        );
        $sureshUser->roles()->sync([$dataEntryRole->id]);
        $sureshEmp = Employee::firstOrCreate(['user_id' => $sureshUser->id], ['employee_code' => 'EMP-1006']);
        EmployeeProfile::firstOrCreate(['employee_id' => $sureshEmp->id], ['gender' => 'Male', 'city' => 'Bangalore', 'state' => 'Karnataka', 'country' => 'India']);
        EmployeeJoiningDetail::firstOrCreate(['employee_id' => $sureshEmp->id], ['joining_date' => '2025-05-01', 'employment_type' => 'Full Time', 'employment_status' => 'Active', 'work_location' => 'Bangalore Office']);
        SalaryStructure::firstOrCreate(['employee_id' => $sureshEmp->id], ['basic_salary' => 22000, 'hra' => 7000, 'allowances' => 3000, 'bonus' => 1000, 'pf_deduction' => 1300, 'other_deductions' => 300, 'gross_salary' => 33000, 'net_salary' => 31400, 'effective_date' => '2025-05-01']);
        LeaveService::initializeBalances($sureshEmp);

        // 8. Seed Sample Finance Requirements
        $sampleFinance = [
            [
                'created_by' => $vikramUser->id,
                'vendor_name' => 'siva',
                'vendor_location' => 'chennai',
                'company_name' => 'digital soluation',
                'selected_candidates_count' => 2,
                'budget' => 40000.00,
                'date' => '2025-11-14',
                'remaining_payment' => 70000.00,
                'status' => 'No Update',
                'note' => 'Initial finance requirement for digital solution candidates.',
            ],
            [
                'created_by' => $vikramUser->id,
                'vendor_name' => 'TechCorp Vendors',
                'vendor_location' => 'Bangalore',
                'company_name' => 'InfoTech Systems',
                'selected_candidates_count' => 5,
                'budget' => 120000.00,
                'date' => '2026-08-10',
                'remaining_payment' => 30000.00,
                'status' => 'In Progress',
                'note' => 'Partially paid requirement for engineering staff deployment.',
            ],
            [
                'created_by' => $vikramUser->id,
                'vendor_name' => 'Global Talent Services',
                'vendor_location' => 'Hyderabad',
                'company_name' => 'Apex Cloud Solutions',
                'selected_candidates_count' => 3,
                'budget' => 85000.00,
                'date' => '2026-07-25',
                'remaining_payment' => 0.00,
                'status' => 'Closed',
                'note' => 'Fully settled finance requirement.',
            ],
        ];

        foreach ($sampleFinance as $sf) {
            FinanceRequirement::firstOrCreate(
                ['vendor_name' => $sf['vendor_name'], 'company_name' => $sf['company_name']],
                $sf
            );
        }
    }
}
