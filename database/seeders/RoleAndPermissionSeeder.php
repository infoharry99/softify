<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Permissions grouped by modules
        $permissionsByModule = [
            'Dashboard' => [
                ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'description' => 'View admin dashboard overview'],
            ],
            'Users' => [
                ['name' => 'View Users', 'slug' => 'users.view', 'description' => 'View user list and details'],
                ['name' => 'Create Users', 'slug' => 'users.create', 'description' => 'Create new user accounts'],
                ['name' => 'Edit Users', 'slug' => 'users.edit', 'description' => 'Edit user profile, roles and direct permissions'],
                ['name' => 'Delete Users', 'slug' => 'users.delete', 'description' => 'Delete user accounts'],
                ['name' => 'Activate/Deactivate Users', 'slug' => 'users.activate', 'description' => 'Enable or disable user status'],
                ['name' => 'Change Password', 'slug' => 'users.change_password', 'description' => 'Reset or change user passwords'],
            ],
            'Roles' => [
                ['name' => 'View Roles', 'slug' => 'roles.view', 'description' => 'View role list and details'],
                ['name' => 'Create Roles', 'slug' => 'roles.create', 'description' => 'Create custom roles'],
                ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'description' => 'Edit role details and status'],
                ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'description' => 'Delete custom roles'],
                ['name' => 'Assign Role Permissions', 'slug' => 'roles.assign_permissions', 'description' => 'Manage permissions for roles'],
            ],
            'Permissions' => [
                ['name' => 'View Permissions', 'slug' => 'permissions.view', 'description' => 'View all system permissions'],
                ['name' => 'Create Permissions', 'slug' => 'permissions.create', 'description' => 'Add new custom permissions'],
                ['name' => 'Edit Permissions', 'slug' => 'permissions.edit', 'description' => 'Edit permission details'],
            ],
            'HR' => [
                ['name' => 'View HR Modules', 'slug' => 'hr.view', 'description' => 'View employee directory, attendance, leaves, and payroll'],
                ['name' => 'Create HR Records', 'slug' => 'hr.create', 'description' => 'Add new employees and HR records'],
                ['name' => 'Edit HR Records', 'slug' => 'hr.edit', 'description' => 'Edit employee records and HR details'],
                ['name' => 'Delete HR Records', 'slug' => 'hr.delete', 'description' => 'Remove employee records'],
                ['name' => 'Export HR Data', 'slug' => 'hr.export', 'description' => 'Export employee dataset'],
            ],
            'Candidates ATS' => [
                ['name' => 'View Candidates ATS', 'slug' => 'candidates.view', 'description' => 'View candidate directory & filter matrix'],
                ['name' => 'Create Candidates ATS', 'slug' => 'candidates.create', 'description' => 'Add new candidates & quick candidate entry'],
                ['name' => 'Edit Candidates ATS', 'slug' => 'candidates.edit', 'description' => 'Edit candidate records and upload resumes'],
                ['name' => 'Delete Candidates ATS', 'slug' => 'candidates.delete', 'description' => 'Remove candidate records'],
            ],
            'Sales' => [
                ['name' => 'View Sales', 'slug' => 'sales.view', 'description' => 'View sales records'],
                ['name' => 'Create Sales', 'slug' => 'sales.create', 'description' => 'Create sales records'],
                ['name' => 'Edit Sales', 'slug' => 'sales.edit', 'description' => 'Edit sales records'],
                ['name' => 'Delete Sales', 'slug' => 'sales.delete', 'description' => 'Delete sales records'],
                ['name' => 'Export Sales', 'slug' => 'sales.export', 'description' => 'Export sales reports'],
            ],
            'Finance' => [
                ['name' => 'View Finance Requirements', 'slug' => 'finance.view', 'description' => 'View vendor & finance requirements'],
                ['name' => 'Create Finance Requirement', 'slug' => 'finance.create', 'description' => 'Create vendor requirement records'],
                ['name' => 'Edit Finance Requirement', 'slug' => 'finance.edit', 'description' => 'Edit vendor requirements'],
                ['name' => 'Delete Finance Requirement', 'slug' => 'finance.delete', 'description' => 'Remove vendor requirements'],
                ['name' => 'Approve Finance', 'slug' => 'finance.approve', 'description' => 'Approve budgets and payments'],
                ['name' => 'Export Finance', 'slug' => 'finance.export', 'description' => 'Export financial statements'],
            ],
            'Reports' => [
                ['name' => 'View Reports', 'slug' => 'reports.view', 'description' => 'View system reports'],
                ['name' => 'Create Reports', 'slug' => 'reports.create', 'description' => 'Generate custom reports'],
                ['name' => 'Export Reports', 'slug' => 'reports.export', 'description' => 'Export report data'],
            ],
            'Activity Logs' => [
                ['name' => 'View Activity Logs', 'slug' => 'activity_logs.view', 'description' => 'View admin audit activity trail'],
            ],
        ];

        $createdPermissions = [];

        foreach ($permissionsByModule as $module => $perms) {
            foreach ($perms as $permData) {
                $permission = Permission::firstOrCreate(
                    ['slug' => $permData['slug']],
                    [
                        'name' => $permData['name'],
                        'module' => $module,
                        'description' => $permData['description'],
                    ]
                );
                $createdPermissions[$permData['slug']] = $permission->id;
            }
        }

        // 2. Default Roles
        $defaultRoles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full access to all system modules and permissions',
                'status' => 'active',
                'permissions' => array_values($createdPermissions),
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access to manage users, roles, and system modules',
                'status' => 'active',
                'permissions' => array_values($createdPermissions),
            ],
            [
                'name' => 'HR',
                'slug' => 'hr',
                'description' => 'Human resources, employees directory, attendance, payroll, and notices management',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['hr.view'] ?? null,
                    $createdPermissions['hr.create'] ?? null,
                    $createdPermissions['hr.edit'] ?? null,
                    $createdPermissions['hr.delete'] ?? null,
                    $createdPermissions['hr.export'] ?? null,
                    $createdPermissions['users.view'] ?? null,
                    $createdPermissions['users.create'] ?? null,
                    $createdPermissions['users.edit'] ?? null,
                    $createdPermissions['roles.view'] ?? null,
                    $createdPermissions['permissions.view'] ?? null,
                    $createdPermissions['activity_logs.view'] ?? null,
                    $createdPermissions['reports.view'] ?? null,
                ],
            ],
            [
                'name' => 'Talent Acquisition',
                'slug' => 'talent-acquisition',
                'description' => 'Talent Acquisition Specialist for candidate directory & filter pipeline',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['candidates.view'] ?? null,
                    $createdPermissions['reports.view'] ?? null,
                ],
            ],
            [
                'name' => 'BDA',
                'slug' => 'bda',
                'description' => 'Business Development Associate for sales, client acquisition & deals',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['sales.view'] ?? null,
                    $createdPermissions['sales.create'] ?? null,
                    $createdPermissions['sales.edit'] ?? null,
                    $createdPermissions['reports.view'] ?? null,
                ],
            ],
            [
                'name' => 'Sales',
                'slug' => 'sales',
                'description' => 'Sales leads and deal management',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['sales.view'] ?? null,
                    $createdPermissions['sales.create'] ?? null,
                    $createdPermissions['sales.edit'] ?? null,
                    $createdPermissions['sales.export'] ?? null,
                    $createdPermissions['reports.view'] ?? null,
                ],
            ],
            [
                'name' => 'Finance',
                'slug' => 'finance',
                'description' => 'Financial requirements, budgets, and vendors',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['finance.view'] ?? null,
                    $createdPermissions['finance.create'] ?? null,
                    $createdPermissions['finance.edit'] ?? null,
                    $createdPermissions['finance.approve'] ?? null,
                    $createdPermissions['finance.export'] ?? null,
                    $createdPermissions['reports.view'] ?? null,
                ],
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Cross-departmental management and reporting',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['hr.view'] ?? null,
                    $createdPermissions['sales.view'] ?? null,
                    $createdPermissions['finance.view'] ?? null,
                    $createdPermissions['reports.view'] ?? null,
                    $createdPermissions['reports.create'] ?? null,
                    $createdPermissions['reports.export'] ?? null,
                ],
            ],
            [
                'name' => 'Data Entry',
                'slug' => 'data-entry',
                'description' => 'Data entry operator for candidate & record creation',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['candidates.view'] ?? null,
                    $createdPermissions['candidates.create'] ?? null,
                    $createdPermissions['sales.create'] ?? null,
                    $createdPermissions['finance.create'] ?? null,
                ],
            ],
            [
                'name' => 'BDA Team Lead',
                'slug' => 'bda-team-lead',
                'description' => 'Team Lead for Business Development Associates (BDA) department',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['sales.view'] ?? null,
                    $createdPermissions['sales.create'] ?? null,
                    $createdPermissions['sales.edit'] ?? null,
                    $createdPermissions['sales.delete'] ?? null,
                    $createdPermissions['sales.export'] ?? null,
                    $createdPermissions['reports.view'] ?? null,
                    $createdPermissions['reports.create'] ?? null,
                ],
            ],
            [
                'name' => 'Talent Acquisition Team Lead',
                'slug' => 'ta-team-lead',
                'description' => 'Team Lead for Talent Acquisition & Recruitment department',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['candidates.view'] ?? null,
                    $createdPermissions['candidates.create'] ?? null,
                    $createdPermissions['candidates.edit'] ?? null,
                    $createdPermissions['candidates.delete'] ?? null,
                    $createdPermissions['reports.view'] ?? null,
                    $createdPermissions['reports.create'] ?? null,
                ],
            ],
            [
                'name' => 'Data Entry Team Lead',
                'slug' => 'data-entry-team-lead',
                'description' => 'Team Lead for Data Entry & Operations department',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['candidates.view'] ?? null,
                    $createdPermissions['candidates.create'] ?? null,
                    $createdPermissions['candidates.edit'] ?? null,
                    $createdPermissions['candidates.delete'] ?? null,
                    $createdPermissions['sales.create'] ?? null,
                    $createdPermissions['finance.create'] ?? null,
                    $createdPermissions['reports.view'] ?? null,
                ],
            ],
            [
                'name' => 'Accountant',
                'slug' => 'accountant',
                'description' => 'Accounting and finance reporting',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['finance.view'] ?? null,
                    $createdPermissions['finance.export'] ?? null,
                    $createdPermissions['reports.view'] ?? null,
                ],
            ],
            [
                'name' => 'Support',
                'slug' => 'support',
                'description' => 'Customer and user support',
                'status' => 'active',
                'permissions' => [
                    $createdPermissions['dashboard.view'] ?? null,
                    $createdPermissions['users.view'] ?? null,
                ],
            ],
        ];

        foreach ($defaultRoles as $roleData) {
            $permissionIds = array_filter($roleData['permissions']);
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(['slug' => $roleData['slug']], $roleData);
            $role->permissions()->sync($permissionIds);
        }

        // 3. Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'mobile' => '9999999999',
                'department' => 'Executive',
                'designation' => 'System Administrator',
                'status' => 'active',
                'password' => Hash::make('password'),
            ]
        );

        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole) {
            $superAdmin->roles()->sync([$superAdminRole->id]);
        }
    }
}
