-- ========================================================
-- Softify Production MySQL Database Dump
-- Generated: 2026-08-16 20:56:42
-- Ready for Import on Live MySQL / phpMyAdmin Server
-- ========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:30";

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `profile_photo` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `department`, `designation`, `status`, `profile_photo`, `last_login_at`) VALUES ('1', 'Super Admin', 'admin@admin.com', NULL, '$2y$10$zEsi9nRWZ9PszNN35gwK8Os7rmbl4d18Sv8A.EKaTS8r1pocaKqx2', NULL, '2026-08-14 21:47:22', '2026-08-16 17:36:26', '9999999999', 'Executive', 'System Administrator', 'active', NULL, '2026-08-16 17:36:26');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `department`, `designation`, `status`, `profile_photo`, `last_login_at`) VALUES ('2', 'Rahul Sharma', 'rahul@example.com', NULL, '$2y$10$26X6dqxWcR2WayBgzyhtyedKnH2j67qYzeR6LbWk8mdUxpwvByZGC', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22', '9876543210', 'Software Development', 'Senior Developer', 'active', NULL, NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `department`, `designation`, `status`, `profile_photo`, `last_login_at`) VALUES ('3', 'Priya Patel', 'priya@example.com', NULL, '$2y$10$mc1YNIBYCRjRi4wzUfFBau9w8/djUc5zw5IZXGLDKPEBWMtx91kqi', NULL, '2026-08-14 21:47:22', '2026-08-16 17:36:11', '9876543211', 'Human Resources', 'HR Executive', 'active', NULL, '2026-08-16 17:36:11');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `department`, `designation`, `status`, `profile_photo`, `last_login_at`) VALUES ('4', 'Vikram Malhotra', 'vikram@example.com', NULL, '$2y$10$WiehVWdJq5Jub.30U1n46OnlvlOYYFaw5BF7u.SKdA6AMmVh6fPUm', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22', '9898776655', 'Finance & Accounts', 'Finance Executive', 'active', NULL, NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `department`, `designation`, `status`, `profile_photo`, `last_login_at`) VALUES ('5', 'Arjun Kapoor', 'arjun@example.com', NULL, '$2y$10$856u9zAV7r/Rzsb4bLuobefKyyvgh0lDwVqgygc8Ma2UB2tVniR.a', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22', '9811223344', 'Business Development', 'Business Development Associate (BDA)', 'active', NULL, NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `department`, `designation`, `status`, `profile_photo`, `last_login_at`) VALUES ('6', 'Ananya Roy', 'ananya@example.com', NULL, '$2y$10$3K7U4noWLTvKMlltjNY74eGsAOtIPoasdMfVmvHQZm5v9TGzh9zh6', NULL, '2026-08-14 21:47:23', '2026-08-15 12:04:04', '9744556677', 'Talent Acquisition & HR', 'Talent Acquisition Specialist', 'active', NULL, '2026-08-15 12:04:04');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `department`, `designation`, `status`, `profile_photo`, `last_login_at`) VALUES ('7', 'Suresh Kumar', 'suresh@example.com', NULL, '$2y$10$odzRtxBUx//p3nYJwYHaHuVdIZGa40jbJpAbou8nialf3QgOK6Awe', NULL, '2026-08-14 21:47:23', '2026-08-15 12:20:29', '9633221100', 'Operations', 'Data Entry Operator', 'active', NULL, '2026-08-15 12:20:29');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `department`, `designation`, `status`, `profile_photo`, `last_login_at`) VALUES ('8', 'Amit Sharma', 'bdalead@talentifyy.com', NULL, '$2y$10$SzWrCSAfm9O.hPllVLgjp.FUw0iw6u4l4Yjg4UUYgmIdzlx8F1Xf2', NULL, '2026-08-16 17:10:10', '2026-08-16 17:30:49', '9811122334', 'BDA', 'BDA Team Lead', 'active', NULL, '2026-08-16 17:30:49');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `department`, `designation`, `status`, `profile_photo`, `last_login_at`) VALUES ('9', 'Neha Verma', 'talead@talentifyy.com', NULL, '$2y$10$QchuosBQNZbru5B4URiyUe5jec/29/Jq5QrXx9Cceef3sHZY2MfCe', NULL, '2026-08-16 17:10:10', '2026-08-16 17:32:42', '9822233445', 'Talent', 'Talent Acquisition Team Lead', 'active', NULL, '2026-08-16 17:32:42');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `mobile`, `department`, `designation`, `status`, `profile_photo`, `last_login_at`) VALUES ('10', 'Rohan Mehta', 'datalead@talentifyy.com', NULL, '$2y$10$sKlsBlJeskqNF372QDzCQO8EDbM.Y1f2NnPau..v9c3qoMUpCg.qS', NULL, '2026-08-16 17:10:10', '2026-08-16 17:18:19', '9833344556', 'Data Entry', 'Data Entry Team Lead', 'active', NULL, '2026-08-16 17:18:19');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` text NOT NULL,
  `exception` text NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp DEFAULT NULL,
  `expires_at` timestamp DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `roles`
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('1', 'Super Admin', 'super-admin', 'Full access to all system modules and permissions', 'active', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('2', 'Admin', 'admin', 'Administrative access to manage users, roles, and system modules', 'active', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('3', 'HR', 'hr', 'Human resources and candidate management', 'active', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('4', 'Talent Acquisition', 'talent-acquisition', 'Talent Acquisition Specialist for recruitment pipeline & candidate directory', 'active', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('5', 'BDA', 'bda', 'Business Development Associate for sales, client acquisition & deals', 'active', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('6', 'Sales', 'sales', 'Sales leads and deal management', 'active', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('7', 'Finance', 'finance', 'Financial requirements, budgets, and vendors', 'active', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('8', 'Manager', 'manager', 'Cross-departmental management and reporting', 'active', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('9', 'Data Entry', 'data-entry', 'Data entry operator for records creation', 'active', '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('10', 'Accountant', 'accountant', 'Accounting and finance reporting', 'active', '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('11', 'Support', 'support', 'Customer and user support', 'active', '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('12', 'BDA Team Lead', 'bda-team-lead', 'Team Lead for Business Development Associates (BDA) department', 'active', '2026-08-16 17:04:37', '2026-08-16 17:04:37');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('13', 'Talent Acquisition Team Lead', 'ta-team-lead', 'Team Lead for Talent Acquisition & Recruitment department', 'active', '2026-08-16 17:04:37', '2026-08-16 17:04:37');
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES ('14', 'Data Entry Team Lead', 'data-entry-team-lead', 'Team Lead for Data Entry & Operations department', 'active', '2026-08-16 17:04:37', '2026-08-16 17:04:37');

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `permissions`
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('1', 'View Dashboard', 'dashboard.view', 'Dashboard', 'View admin dashboard overview', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('2', 'View Users', 'users.view', 'Users', 'View user list and details', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('3', 'Create Users', 'users.create', 'Users', 'Create new user accounts', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('4', 'Edit Users', 'users.edit', 'Users', 'Edit user profile, roles and direct permissions', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('5', 'Delete Users', 'users.delete', 'Users', 'Delete user accounts', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('6', 'Activate/Deactivate Users', 'users.activate', 'Users', 'Enable or disable user status', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('7', 'Change Password', 'users.change_password', 'Users', 'Reset or change user passwords', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('8', 'View Roles', 'roles.view', 'Roles', 'View role list and details', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('9', 'Create Roles', 'roles.create', 'Roles', 'Create custom roles', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('10', 'Edit Roles', 'roles.edit', 'Roles', 'Edit role details and status', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('11', 'Delete Roles', 'roles.delete', 'Roles', 'Delete custom roles', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('12', 'Assign Role Permissions', 'roles.assign_permissions', 'Roles', 'Manage permissions for roles', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('13', 'View Permissions', 'permissions.view', 'Permissions', 'View all system permissions', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('14', 'Create Permissions', 'permissions.create', 'Permissions', 'Add new custom permissions', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('15', 'Edit Permissions', 'permissions.edit', 'Permissions', 'Edit permission details', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('16', 'View HR Candidates', 'hr.view', 'HR', 'View candidate/student directory', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('17', 'Create Candidates', 'hr.create', 'HR', 'Add new candidates/students', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('18', 'Edit Candidates', 'hr.edit', 'HR', 'Edit candidate records and upload resumes', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('19', 'Delete Candidates', 'hr.delete', 'HR', 'Remove candidate records', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('20', 'Export HR Data', 'hr.export', 'HR', 'Export candidates dataset', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('21', 'View Sales', 'sales.view', 'Sales', 'View sales records', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('22', 'Create Sales', 'sales.create', 'Sales', 'Create sales records', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('23', 'Edit Sales', 'sales.edit', 'Sales', 'Edit sales records', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('24', 'Delete Sales', 'sales.delete', 'Sales', 'Delete sales records', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('25', 'Export Sales', 'sales.export', 'Sales', 'Export sales reports', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('26', 'View Finance Requirements', 'finance.view', 'Finance', 'View vendor & finance requirements', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('27', 'Create Finance Requirement', 'finance.create', 'Finance', 'Create vendor requirement records', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('28', 'Edit Finance Requirement', 'finance.edit', 'Finance', 'Edit vendor requirements', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('29', 'Delete Finance Requirement', 'finance.delete', 'Finance', 'Remove vendor requirements', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('30', 'Approve Finance', 'finance.approve', 'Finance', 'Approve budgets and payments', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('31', 'Export Finance', 'finance.export', 'Finance', 'Export financial statements', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('32', 'View Reports', 'reports.view', 'Reports', 'View system reports', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('33', 'Create Reports', 'reports.create', 'Reports', 'Generate custom reports', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('34', 'Export Reports', 'reports.export', 'Reports', 'Export report data', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('35', 'View Activity Logs', 'activity_logs.view', 'Activity Logs', 'View admin audit activity trail', '2026-08-14 21:47:21', '2026-08-14 21:47:21');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('36', 'View Candidates ATS', 'candidates.view', 'Candidates ATS', 'View candidate directory & filter matrix', '2026-08-16 17:04:37', '2026-08-16 17:04:37');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('37', 'Create Candidates ATS', 'candidates.create', 'Candidates ATS', 'Add new candidates & quick candidate entry', '2026-08-16 17:04:37', '2026-08-16 17:04:37');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('38', 'Edit Candidates ATS', 'candidates.edit', 'Candidates ATS', 'Edit candidate records and upload resumes', '2026-08-16 17:04:37', '2026-08-16 17:04:37');
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES ('39', 'Delete Candidates ATS', 'candidates.delete', 'Candidates ATS', 'Remove candidate records', '2026-08-16 17:04:37', '2026-08-16 17:04:37');

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `role_user`
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES ('1', '1');
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES ('2', '3');
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES ('3', '3');
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES ('4', '7');
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES ('5', '5');
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES ('6', '4');
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES ('7', '9');
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES ('8', '12');
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES ('9', '13');
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES ('10', '14');

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `permission_role`
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('2', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('3', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('4', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('5', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('6', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('7', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('8', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('9', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('10', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('11', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('12', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('13', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('14', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('15', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('16', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('17', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('18', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('19', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('20', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('21', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('22', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('23', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('24', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('25', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('26', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('27', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('28', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('29', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('30', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('31', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('33', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('34', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('35', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('2', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('3', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('4', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('5', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('6', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('7', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('8', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('9', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('10', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('11', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('12', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('13', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('14', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('15', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('16', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('17', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('18', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('19', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('20', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('21', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('22', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('23', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('24', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('25', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('26', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('27', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('28', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('29', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('30', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('31', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('33', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('34', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('35', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '4');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '4');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '5');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('21', '5');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('22', '5');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('23', '5');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '5');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '6');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('21', '6');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('22', '6');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('23', '6');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('25', '6');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '6');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('26', '7');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('27', '7');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('28', '7');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('30', '7');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('31', '7');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '8');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('16', '8');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('21', '8');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('26', '8');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '8');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('33', '8');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('34', '8');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '9');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('22', '9');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('27', '9');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '10');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('26', '10');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('31', '10');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '10');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '11');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('2', '11');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('2', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('3', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('4', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('8', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('13', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('35', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('36', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('37', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('38', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('39', '1');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('36', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('37', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('38', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('39', '2');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('16', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('17', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('18', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('19', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('20', '3');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('36', '4');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('36', '9');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('37', '9');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '12');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('21', '12');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('22', '12');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('23', '12');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('24', '12');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('25', '12');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '12');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('33', '12');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '13');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('36', '13');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('37', '13');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('38', '13');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('39', '13');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '13');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('33', '13');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '14');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('36', '14');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('37', '14');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('38', '14');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('39', '14');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('22', '14');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('27', '14');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '14');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('1', '7');
INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES ('32', '7');

DROP TABLE IF EXISTS `permission_user`;
CREATE TABLE `permission_user` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `permission_user`
INSERT INTO `permission_user` (`permission_id`, `user_id`) VALUES ('26', '4');
INSERT INTO `permission_user` (`permission_id`, `user_id`) VALUES ('27', '4');
INSERT INTO `permission_user` (`permission_id`, `user_id`) VALUES ('28', '4');
INSERT INTO `permission_user` (`permission_id`, `user_id`) VALUES ('29', '4');
INSERT INTO `permission_user` (`permission_id`, `user_id`) VALUES ('30', '4');
INSERT INTO `permission_user` (`permission_id`, `user_id`) VALUES ('31', '4');

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(255) NOT NULL,
  `reporting_manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `employees`
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `reporting_manager_id`, `created_at`, `updated_at`) VALUES ('1', '2', 'EMP-1001', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `reporting_manager_id`, `created_at`, `updated_at`) VALUES ('2', '3', 'EMP-1002', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `reporting_manager_id`, `created_at`, `updated_at`) VALUES ('3', '4', 'EMP-1003', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `reporting_manager_id`, `created_at`, `updated_at`) VALUES ('4', '5', 'EMP-1004', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `reporting_manager_id`, `created_at`, `updated_at`) VALUES ('5', '6', 'EMP-1005', NULL, '2026-08-14 21:47:23', '2026-08-14 21:47:23');
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `reporting_manager_id`, `created_at`, `updated_at`) VALUES ('6', '7', 'EMP-1006', NULL, '2026-08-14 21:47:23', '2026-08-14 21:47:23');
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `reporting_manager_id`, `created_at`, `updated_at`) VALUES ('7', '1', 'EMP-0001', NULL, '2026-08-15 11:33:22', '2026-08-15 11:33:22');
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `reporting_manager_id`, `created_at`, `updated_at`) VALUES ('8', '8', 'EMP-8001', NULL, '2026-08-16 17:10:10', '2026-08-16 17:10:10');
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `reporting_manager_id`, `created_at`, `updated_at`) VALUES ('9', '9', 'EMP-8002', NULL, '2026-08-16 17:10:10', '2026-08-16 17:10:10');
INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `reporting_manager_id`, `created_at`, `updated_at`) VALUES ('10', '10', 'EMP-8003', NULL, '2026-08-16 17:10:10', '2026-08-16 17:10:10');

DROP TABLE IF EXISTS `employee_profiles`;
CREATE TABLE `employee_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `employee_profiles`
INSERT INTO `employee_profiles` (`id`, `employee_id`, `dob`, `gender`, `address`, `city`, `state`, `country`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES ('1', '1', NULL, 'Male', NULL, 'Mumbai', 'Maharashtra', 'India', NULL, NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employee_profiles` (`id`, `employee_id`, `dob`, `gender`, `address`, `city`, `state`, `country`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES ('2', '2', NULL, 'Female', NULL, 'Ahmedabad', 'Gujarat', 'India', NULL, NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employee_profiles` (`id`, `employee_id`, `dob`, `gender`, `address`, `city`, `state`, `country`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES ('3', '3', NULL, 'Male', NULL, 'Chennai', 'Tamil Nadu', 'India', NULL, NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employee_profiles` (`id`, `employee_id`, `dob`, `gender`, `address`, `city`, `state`, `country`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES ('4', '4', NULL, 'Male', NULL, 'Delhi', 'Delhi', 'India', NULL, NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employee_profiles` (`id`, `employee_id`, `dob`, `gender`, `address`, `city`, `state`, `country`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES ('5', '5', NULL, 'Female', NULL, 'Kolkata', 'West Bengal', 'India', NULL, NULL, '2026-08-14 21:47:23', '2026-08-14 21:47:23');
INSERT INTO `employee_profiles` (`id`, `employee_id`, `dob`, `gender`, `address`, `city`, `state`, `country`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES ('6', '6', NULL, 'Male', NULL, 'Bangalore', 'Karnataka', 'India', NULL, NULL, '2026-08-14 21:47:23', '2026-08-14 21:47:23');
INSERT INTO `employee_profiles` (`id`, `employee_id`, `dob`, `gender`, `address`, `city`, `state`, `country`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES ('7', '8', NULL, 'Male', NULL, 'Delhi', 'Delhi', 'India', NULL, NULL, '2026-08-16 17:10:10', '2026-08-16 17:10:10');
INSERT INTO `employee_profiles` (`id`, `employee_id`, `dob`, `gender`, `address`, `city`, `state`, `country`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES ('8', '9', NULL, 'Female', NULL, 'Pune', 'Maharashtra', 'India', NULL, NULL, '2026-08-16 17:10:10', '2026-08-16 17:10:10');
INSERT INTO `employee_profiles` (`id`, `employee_id`, `dob`, `gender`, `address`, `city`, `state`, `country`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES ('9', '10', NULL, 'Male', NULL, 'Mumbai', 'Maharashtra', 'India', NULL, NULL, '2026-08-16 17:10:10', '2026-08-16 17:10:10');

DROP TABLE IF EXISTS `employee_joining_details`;
CREATE TABLE `employee_joining_details` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `joining_date` date NOT NULL,
  `employment_type` varchar(255) NOT NULL DEFAULT 'Full Time',
  `employment_status` varchar(255) NOT NULL DEFAULT 'Active',
  `probation_end_date` date DEFAULT NULL,
  `confirmation_date` date DEFAULT NULL,
  `notice_period_days` int(11) NOT NULL DEFAULT '30',
  `work_location` varchar(255) NOT NULL DEFAULT 'Office',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `employee_joining_details`
INSERT INTO `employee_joining_details` (`id`, `employee_id`, `joining_date`, `employment_type`, `employment_status`, `probation_end_date`, `confirmation_date`, `notice_period_days`, `work_location`, `remarks`, `created_at`, `updated_at`) VALUES ('1', '1', '2025-01-15 00:00:00', 'Full Time', 'Active', NULL, NULL, '30', 'Mumbai HQ', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employee_joining_details` (`id`, `employee_id`, `joining_date`, `employment_type`, `employment_status`, `probation_end_date`, `confirmation_date`, `notice_period_days`, `work_location`, `remarks`, `created_at`, `updated_at`) VALUES ('2', '2', '2025-03-01 00:00:00', 'Full Time', 'Active', NULL, NULL, '30', 'Ahmedabad Regional Office', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employee_joining_details` (`id`, `employee_id`, `joining_date`, `employment_type`, `employment_status`, `probation_end_date`, `confirmation_date`, `notice_period_days`, `work_location`, `remarks`, `created_at`, `updated_at`) VALUES ('3', '3', '2025-02-01 00:00:00', 'Full Time', 'Active', NULL, NULL, '30', 'Chennai Office', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employee_joining_details` (`id`, `employee_id`, `joining_date`, `employment_type`, `employment_status`, `probation_end_date`, `confirmation_date`, `notice_period_days`, `work_location`, `remarks`, `created_at`, `updated_at`) VALUES ('4', '4', '2025-04-01 00:00:00', 'Full Time', 'Active', NULL, NULL, '30', 'Delhi Regional Office', NULL, '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `employee_joining_details` (`id`, `employee_id`, `joining_date`, `employment_type`, `employment_status`, `probation_end_date`, `confirmation_date`, `notice_period_days`, `work_location`, `remarks`, `created_at`, `updated_at`) VALUES ('5', '5', '2025-03-15 00:00:00', 'Full Time', 'Active', NULL, NULL, '30', 'Kolkata Hub', NULL, '2026-08-14 21:47:23', '2026-08-14 21:47:23');
INSERT INTO `employee_joining_details` (`id`, `employee_id`, `joining_date`, `employment_type`, `employment_status`, `probation_end_date`, `confirmation_date`, `notice_period_days`, `work_location`, `remarks`, `created_at`, `updated_at`) VALUES ('6', '6', '2025-05-01 00:00:00', 'Full Time', 'Active', NULL, NULL, '30', 'Bangalore Office', NULL, '2026-08-14 21:47:23', '2026-08-14 21:47:23');
INSERT INTO `employee_joining_details` (`id`, `employee_id`, `joining_date`, `employment_type`, `employment_status`, `probation_end_date`, `confirmation_date`, `notice_period_days`, `work_location`, `remarks`, `created_at`, `updated_at`) VALUES ('7', '8', '2025-02-01 00:00:00', 'Full Time', 'Active', NULL, NULL, '30', 'Delhi Office', NULL, '2026-08-16 17:10:10', '2026-08-16 17:10:10');
INSERT INTO `employee_joining_details` (`id`, `employee_id`, `joining_date`, `employment_type`, `employment_status`, `probation_end_date`, `confirmation_date`, `notice_period_days`, `work_location`, `remarks`, `created_at`, `updated_at`) VALUES ('8', '9', '2025-02-15 00:00:00', 'Full Time', 'Active', NULL, NULL, '30', 'Pune Office', NULL, '2026-08-16 17:10:10', '2026-08-16 17:10:10');
INSERT INTO `employee_joining_details` (`id`, `employee_id`, `joining_date`, `employment_type`, `employment_status`, `probation_end_date`, `confirmation_date`, `notice_period_days`, `work_location`, `remarks`, `created_at`, `updated_at`) VALUES ('9', '10', '2025-03-01 00:00:00', 'Full Time', 'Active', NULL, NULL, '30', 'Mumbai HQ', NULL, '2026-08-16 17:10:10', '2026-08-16 17:10:10');

DROP TABLE IF EXISTS `leave_types`;
CREATE TABLE `leave_types` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `days_allowed_per_year` int(11) NOT NULL DEFAULT '12',
  `is_paid` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `leave_types`
INSERT INTO `leave_types` (`id`, `name`, `slug`, `days_allowed_per_year`, `is_paid`, `created_at`, `updated_at`) VALUES ('1', 'Casual Leave', 'casual-leave', '12', '1', '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `leave_types` (`id`, `name`, `slug`, `days_allowed_per_year`, `is_paid`, `created_at`, `updated_at`) VALUES ('2', 'Sick Leave', 'sick-leave', '10', '1', '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `leave_types` (`id`, `name`, `slug`, `days_allowed_per_year`, `is_paid`, `created_at`, `updated_at`) VALUES ('3', 'Earned Leave', 'earned-leave', '15', '1', '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `leave_types` (`id`, `name`, `slug`, `days_allowed_per_year`, `is_paid`, `created_at`, `updated_at`) VALUES ('4', 'Paid Leave', 'paid-leave', '10', '1', '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `leave_types` (`id`, `name`, `slug`, `days_allowed_per_year`, `is_paid`, `created_at`, `updated_at`) VALUES ('5', 'Unpaid Leave', 'unpaid-leave', '0', '0', '2026-08-14 21:47:22', '2026-08-14 21:47:22');
INSERT INTO `leave_types` (`id`, `name`, `slug`, `days_allowed_per_year`, `is_paid`, `created_at`, `updated_at`) VALUES ('6', 'Half Day', 'half-day', '6', '1', '2026-08-14 21:47:22', '2026-08-14 21:47:22');

DROP TABLE IF EXISTS `leave_applications`;
CREATE TABLE `leave_applications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type_id` bigint(20) UNSIGNED NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `total_days` varchar(255) NOT NULL,
  `is_half_day` tinyint(1) NOT NULL DEFAULT '0',
  `reason` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `admin_remark` text DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `attendance_sessions`;
CREATE TABLE `attendance_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `login_at` timestamp NOT NULL,
  `logout_at` timestamp DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `attendance_sessions`
INSERT INTO `attendance_sessions` (`id`, `attendance_id`, `employee_id`, `login_at`, `logout_at`, `duration_minutes`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('1', '1', '2', '2026-08-16 15:41:24', '2026-08-16 15:41:30', '0', 'Logged Out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 15:41:24', '2026-08-16 15:41:30');
INSERT INTO `attendance_sessions` (`id`, `attendance_id`, `employee_id`, `login_at`, `logout_at`, `duration_minutes`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('2', '1', '2', '2026-08-16 15:41:42', '2026-08-16 15:41:49', '0', 'Logged Out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 15:41:42', '2026-08-16 15:41:49');
INSERT INTO `attendance_sessions` (`id`, `attendance_id`, `employee_id`, `login_at`, `logout_at`, `duration_minutes`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('3', '1', '2', '2026-08-16 15:45:41', '2026-08-16 15:45:54', '0', 'Auto Closed', '127.0.0.1', 'Symfony', '2026-08-16 15:45:41', '2026-08-16 15:45:54');
INSERT INTO `attendance_sessions` (`id`, `attendance_id`, `employee_id`, `login_at`, `logout_at`, `duration_minutes`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('4', '1', '2', '2026-08-16 15:45:54', '2026-08-16 15:45:54', '0', 'Logged Out', '127.0.0.1', 'Symfony', '2026-08-16 15:45:54', '2026-08-16 15:45:54');
INSERT INTO `attendance_sessions` (`id`, `attendance_id`, `employee_id`, `login_at`, `logout_at`, `duration_minutes`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('5', '1', '2', '2026-08-16 15:46:04', '2026-08-16 15:46:04', '0', 'Logged Out', '127.0.0.1', 'Symfony', '2026-08-16 15:46:04', '2026-08-16 15:46:04');
INSERT INTO `attendance_sessions` (`id`, `attendance_id`, `employee_id`, `login_at`, `logout_at`, `duration_minutes`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('6', '1', '2', '2026-08-16 15:47:19', '2026-08-16 15:47:22', '0', 'Logged Out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 15:47:19', '2026-08-16 15:47:22');

DROP TABLE IF EXISTS `attendance_breaks`;
CREATE TABLE `attendance_breaks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `attendance_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `started_at` timestamp NOT NULL,
  `ended_at` timestamp DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT '0',
  `is_exceeded` tinyint(1) NOT NULL DEFAULT '0',
  `exceeded_minutes` int(11) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `salary_structures`;
CREATE TABLE `salary_structures` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL DEFAULT '0',
  `hra` decimal(12,2) NOT NULL DEFAULT '0',
  `conveyance` decimal(12,2) NOT NULL DEFAULT '0',
  `allowances` decimal(12,2) NOT NULL DEFAULT '0',
  `bonus` decimal(12,2) NOT NULL DEFAULT '0',
  `incentives` decimal(12,2) NOT NULL DEFAULT '0',
  `pf_deduction` decimal(12,2) NOT NULL DEFAULT '0',
  `esi_deduction` decimal(12,2) NOT NULL DEFAULT '0',
  `pt_deduction` decimal(12,2) NOT NULL DEFAULT '0',
  `tds_deduction` decimal(12,2) NOT NULL DEFAULT '0',
  `other_deductions` decimal(12,2) NOT NULL DEFAULT '0',
  `gross_salary` decimal(12,2) NOT NULL DEFAULT '0',
  `net_salary` decimal(12,2) NOT NULL DEFAULT '0',
  `effective_date` date NOT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `salary_structures`
INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `hra`, `conveyance`, `allowances`, `bonus`, `incentives`, `pf_deduction`, `esi_deduction`, `pt_deduction`, `tds_deduction`, `other_deductions`, `gross_salary`, `net_salary`, `effective_date`, `created_at`, `updated_at`) VALUES ('1', '1', '35000', '12000', '0', '5000', '2000', '0', '0', '0', '0', '0', '500', '54000', '51400', '2025-01-15 00:00:00', '2026-08-14 21:47:22', '2026-08-16 16:30:30');
INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `hra`, `conveyance`, `allowances`, `bonus`, `incentives`, `pf_deduction`, `esi_deduction`, `pt_deduction`, `tds_deduction`, `other_deductions`, `gross_salary`, `net_salary`, `effective_date`, `created_at`, `updated_at`) VALUES ('2', '2', '25000', '8000', '0', '4000', '1000', '0', '0', '0', '0', '0', '300', '38000', '36200', '2025-03-01 00:00:00', '2026-08-14 21:47:22', '2026-08-16 16:30:30');
INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `hra`, `conveyance`, `allowances`, `bonus`, `incentives`, `pf_deduction`, `esi_deduction`, `pt_deduction`, `tds_deduction`, `other_deductions`, `gross_salary`, `net_salary`, `effective_date`, `created_at`, `updated_at`) VALUES ('3', '3', '32000', '10000', '0', '4500', '1500', '0', '0', '0', '0', '0', '400', '48000', '45700', '2025-02-01 00:00:00', '2026-08-14 21:47:22', '2026-08-16 16:30:30');
INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `hra`, `conveyance`, `allowances`, `bonus`, `incentives`, `pf_deduction`, `esi_deduction`, `pt_deduction`, `tds_deduction`, `other_deductions`, `gross_salary`, `net_salary`, `effective_date`, `created_at`, `updated_at`) VALUES ('4', '4', '28000', '9000', '0', '4000', '2000', '0', '0', '0', '0', '0', '400', '43000', '41000', '2025-04-01 00:00:00', '2026-08-14 21:47:22', '2026-08-16 16:30:30');
INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `hra`, `conveyance`, `allowances`, `bonus`, `incentives`, `pf_deduction`, `esi_deduction`, `pt_deduction`, `tds_deduction`, `other_deductions`, `gross_salary`, `net_salary`, `effective_date`, `created_at`, `updated_at`) VALUES ('5', '5', '30000', '9500', '0', '4200', '1500', '0', '0', '0', '0', '0', '400', '45200', '43000', '2025-03-15 00:00:00', '2026-08-14 21:47:23', '2026-08-16 16:30:30');
INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `hra`, `conveyance`, `allowances`, `bonus`, `incentives`, `pf_deduction`, `esi_deduction`, `pt_deduction`, `tds_deduction`, `other_deductions`, `gross_salary`, `net_salary`, `effective_date`, `created_at`, `updated_at`) VALUES ('6', '6', '22000', '7000', '0', '3000', '1000', '0', '0', '0', '0', '0', '300', '33000', '31400', '2025-05-01 00:00:00', '2026-08-14 21:47:23', '2026-08-16 16:30:30');
INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `hra`, `conveyance`, `allowances`, `bonus`, `incentives`, `pf_deduction`, `esi_deduction`, `pt_deduction`, `tds_deduction`, `other_deductions`, `gross_salary`, `net_salary`, `effective_date`, `created_at`, `updated_at`) VALUES ('7', '7', '20000', '5000', '0', '3000', '0', '0', '0', '0', '0', '0', '0', '28000', '28000', '2026-08-16 16:30:30', '2026-08-16 16:30:30', '2026-08-16 16:30:30');
INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `hra`, `conveyance`, `allowances`, `bonus`, `incentives`, `pf_deduction`, `esi_deduction`, `pt_deduction`, `tds_deduction`, `other_deductions`, `gross_salary`, `net_salary`, `effective_date`, `created_at`, `updated_at`) VALUES ('8', '8', '30000', '10000', '0', '5000', '3000', '0', '0', '0', '0', '0', '500', '45000', '47500', '2025-02-01 00:00:00', '2026-08-16 17:10:10', '2026-08-16 17:10:10');
INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `hra`, `conveyance`, `allowances`, `bonus`, `incentives`, `pf_deduction`, `esi_deduction`, `pt_deduction`, `tds_deduction`, `other_deductions`, `gross_salary`, `net_salary`, `effective_date`, `created_at`, `updated_at`) VALUES ('9', '9', '28000', '9000', '0', '4000', '2000', '0', '0', '0', '0', '0', '400', '41000', '42600', '2025-02-15 00:00:00', '2026-08-16 17:10:10', '2026-08-16 17:10:10');
INSERT INTO `salary_structures` (`id`, `employee_id`, `basic_salary`, `hra`, `conveyance`, `allowances`, `bonus`, `incentives`, `pf_deduction`, `esi_deduction`, `pt_deduction`, `tds_deduction`, `other_deductions`, `gross_salary`, `net_salary`, `effective_date`, `created_at`, `updated_at`) VALUES ('10', '10', '26000', '8000', '0', '4000', '1500', '0', '0', '0', '0', '0', '300', '38000', '39200', '2025-03-01 00:00:00', '2026-08-16 17:10:10', '2026-08-16 17:10:10');

DROP TABLE IF EXISTS `candidates`;
CREATE TABLE `candidates` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hr_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `skills` text NOT NULL,
  `experience` varchar(255) NOT NULL DEFAULT '0',
  `job_type` varchar(255) NOT NULL DEFAULT 'Full Time',
  `notice_period` varchar(255) NOT NULL DEFAULT 'Immediate',
  `current_ctc` decimal(12,2) DEFAULT NULL,
  `expected_ctc` decimal(12,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Applied',
  `resume` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `last_updated_by` int(11) DEFAULT NULL,
  `is_highlighted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `edited_resume` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `candidates`
INSERT INTO `candidates` (`id`, `hr_id`, `company_name`, `name`, `email`, `phone`, `location`, `skills`, `experience`, `job_type`, `notice_period`, `current_ctc`, `expected_ctc`, `status`, `resume`, `note`, `last_updated_by`, `is_highlighted`, `created_at`, `updated_at`, `job_title`, `edited_resume`) VALUES ('1', NULL, NULL, 'Rahul Sharma', 'rahul@example.com', '9876543210', 'Hyderabad', 'Python, Django, AI', '4.5', 'Full Time', '15 Days', '900000', '1200000', 'Applied', NULL, NULL, NULL, '0', '2026-08-16 17:42:02', '2026-08-16 17:42:02', 'Senior Python Developer', NULL);
INSERT INTO `candidates` (`id`, `hr_id`, `company_name`, `name`, `email`, `phone`, `location`, `skills`, `experience`, `job_type`, `notice_period`, `current_ctc`, `expected_ctc`, `status`, `resume`, `note`, `last_updated_by`, `is_highlighted`, `created_at`, `updated_at`, `job_title`, `edited_resume`) VALUES ('2', NULL, NULL, 'Amit Test Candidate', 'amittest@example.com', '9876543211', 'Bangalore', 'Java, Spring Boot, Microservices', '5', 'Full Time', '30 Days', NULL, NULL, 'Screening', 'candidate_resumes/original_resume_raw.pdf', NULL, '1', '0', '2026-08-16 17:50:45', '2026-08-16 17:53:08', 'Senior Java Developer', 'candidate_edited_resumes/cOrHzdwgMvbf0wfLiBYpBzdIJi01z9Jjtmppxxlm.pdf');

DROP TABLE IF EXISTS `finance_requirements`;
CREATE TABLE `finance_requirements` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_by` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `vendor_location` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `selected_candidates_count` int(11) NOT NULL DEFAULT '0',
  `budget` decimal(12,2) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `remaining_payment` decimal(12,2) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT 'No Update',
  `note` text DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `finance_requirements`
INSERT INTO `finance_requirements` (`id`, `created_by`, `vendor_name`, `vendor_location`, `company_name`, `selected_candidates_count`, `budget`, `date`, `remaining_payment`, `status`, `note`, `created_at`, `updated_at`) VALUES ('1', '4', 'siva', 'chennai', 'digital soluation', '2', '40000', '2025-11-14 00:00:00', '70000', 'No Update', 'Initial finance requirement for digital solution candidates.', '2026-08-14 21:47:23', '2026-08-14 21:47:23');
INSERT INTO `finance_requirements` (`id`, `created_by`, `vendor_name`, `vendor_location`, `company_name`, `selected_candidates_count`, `budget`, `date`, `remaining_payment`, `status`, `note`, `created_at`, `updated_at`) VALUES ('2', '4', 'TechCorp Vendors', 'Bangalore', 'InfoTech Systems', '5', '120000', '2026-08-10 00:00:00', '30000', 'In Progress', 'Partially paid requirement for engineering staff deployment.', '2026-08-14 21:47:23', '2026-08-14 21:47:23');
INSERT INTO `finance_requirements` (`id`, `created_by`, `vendor_name`, `vendor_location`, `company_name`, `selected_candidates_count`, `budget`, `date`, `remaining_payment`, `status`, `note`, `created_at`, `updated_at`) VALUES ('3', '4', 'Global Talent Services', 'Hyderabad', 'Apex Cloud Solutions', '3', '85000', '2026-07-25 00:00:00', '0', 'Closed', 'Fully settled finance requirement.', '2026-08-14 21:47:23', '2026-08-14 21:47:23');

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `audience` varchar(255) NOT NULL DEFAULT 'All Employees',
  `target_payload` text DEFAULT NULL,
  `published_by` int(11) DEFAULT NULL,
  `published_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `data` text DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `notifications`
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('1', '2', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹0.00', '0', NULL, '2026-08-15 11:44:24', '2026-08-15 11:44:24');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('2', '2', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹55,500.00', '0', NULL, '2026-08-16 16:30:30', '2026-08-16 16:30:30');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('3', '3', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹1,925.81', '0', NULL, '2026-08-16 16:30:30', '2026-08-16 16:30:30');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('4', '4', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹49,100.00', '0', NULL, '2026-08-16 16:30:30', '2026-08-16 16:30:30');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('5', '5', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹44,600.00', '0', NULL, '2026-08-16 16:30:30', '2026-08-16 16:30:30');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('6', '6', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹46,300.00', '0', NULL, '2026-08-16 16:30:30', '2026-08-16 16:30:30');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('7', '7', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹33,700.00', '0', NULL, '2026-08-16 16:30:30', '2026-08-16 16:30:30');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('8', '1', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹28,000.00', '0', NULL, '2026-08-16 16:30:30', '2026-08-16 16:30:30');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('9', '6', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹46,300.00', '0', NULL, '2026-08-16 16:36:22', '2026-08-16 16:36:22');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('10', '6', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹46,300.00', '0', NULL, '2026-08-16 16:36:26', '2026-08-16 16:36:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `data`, `created_at`, `updated_at`) VALUES ('11', '2', 'payroll_generated', 'Salary Slip Generated', 'Your salary slip for 2026-08 has been finalized. Net Amount: ₹55,500.00', '0', NULL, '2026-08-16 16:53:14', '2026-08-16 16:53:14');

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `target_type` varchar(255) DEFAULT NULL,
  `target_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp DEFAULT NULL,
  `updated_at` timestamp DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `activity_logs`
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('1', '1', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 11:33:21', '2026-08-15 11:33:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('2', '1', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 11:34:07', '2026-08-15 11:34:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('3', '3', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 11:34:17', '2026-08-15 11:34:17');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('4', '3', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 11:36:59', '2026-08-15 11:36:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('5', '3', 'Payroll Processed', 'App\\Models\\Employee', '1', 'Processed payroll for Rahul Sharma for month 2026-08', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 11:44:24', '2026-08-15 11:44:24');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('6', '3', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 11:53:11', '2026-08-15 11:53:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('7', '3', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 11:53:49', '2026-08-15 11:53:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('8', '3', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 12:03:58', '2026-08-15 12:03:58');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('9', '6', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 12:04:04', '2026-08-15 12:04:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('10', '6', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 12:18:09', '2026-08-15 12:18:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('11', '3', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 12:18:15', '2026-08-15 12:18:15');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('12', '3', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 12:20:15', '2026-08-15 12:20:15');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('13', '7', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-15 12:20:29', '2026-08-15 12:20:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('14', '3', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 15:37:50', '2026-08-16 15:37:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('15', '3', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Symfony', '2026-08-16 15:45:54', '2026-08-16 15:45:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('16', '3', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Symfony', '2026-08-16 15:46:04', '2026-08-16 15:46:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('17', '3', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 15:47:22', '2026-08-16 15:47:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('18', '3', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 15:47:25', '2026-08-16 15:47:25');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('19', '1', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 16:27:58', '2026-08-16 16:27:58');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('20', '3', 'Payroll Processed', 'App\\Models\\Employee', '5', 'Processed payroll for Ananya Roy for month 2026-08', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 16:36:22', '2026-08-16 16:36:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('21', '3', 'Payroll Processed', 'App\\Models\\Employee', '5', 'Processed payroll for Ananya Roy for month 2026-08', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 16:36:26', '2026-08-16 16:36:26');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('22', '3', 'Leave Balances Modified', 'App\\Models\\Employee', '1', 'Updated leave quotas for Rahul Sharma', '127.0.0.1', 'Symfony', '2026-08-16 16:46:21', '2026-08-16 16:46:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('23', '1', 'Announcement Created', 'App\\Models\\Announcement', '1', 'Published company announcement \'📢 New Notice\'', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 16:49:42', '2026-08-16 16:49:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('24', '1', 'Announcement Updated', 'App\\Models\\Announcement', '2', 'Updated company announcement \'Updated Notice Title\'', '127.0.0.1', 'Symfony', '2026-08-16 16:50:45', '2026-08-16 16:50:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('25', '1', 'Announcement Deleted', 'App\\Models\\Announcement', '2', 'Deleted company announcement \'Updated Notice Title\'', '127.0.0.1', 'Symfony', '2026-08-16 16:50:45', '2026-08-16 16:50:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('26', '1', 'Announcement Deleted', 'App\\Models\\Announcement', '1', 'Deleted company announcement \'📢 New Notice\'', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 16:52:56', '2026-08-16 16:52:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('27', '1', 'Payroll Processed', 'App\\Models\\Employee', '1', 'Processed payroll for Rahul Sharma for month 2026-08', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 16:53:14', '2026-08-16 16:53:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('28', '1', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:13:04', '2026-08-16 17:13:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('29', '8', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:13:16', '2026-08-16 17:13:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('30', '8', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:17:39', '2026-08-16 17:17:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('31', '9', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:17:50', '2026-08-16 17:17:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('32', '9', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:18:11', '2026-08-16 17:18:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('33', '10', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:18:19', '2026-08-16 17:18:19');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('34', '10', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:18:25', '2026-08-16 17:18:25');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('35', '8', 'BDA Work Assigned', 'App\\Models\\BdaWorkAssignment', '1', 'Assigned BDA daily target for Aug 16, 2026 to Vikram Malhotra', '127.0.0.1', 'Symfony', '2026-08-16 17:27:29', '2026-08-16 17:27:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('36', '4', 'BDA Work Updated by Employee', 'App\\Models\\BdaWorkAssignment', '1', 'Updated daily work status to In Progress for assignment #1', '127.0.0.1', 'Symfony', '2026-08-16 17:27:29', '2026-08-16 17:27:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('37', '8', 'BDA Work Reviewed by Lead', 'App\\Models\\BdaWorkAssignment', '1', 'Updated status to Done and added notes for assignment #1', '127.0.0.1', 'Symfony', '2026-08-16 17:27:29', '2026-08-16 17:27:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('38', '8', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:30:34', '2026-08-16 17:30:34');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('39', '8', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:30:49', '2026-08-16 17:30:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('40', '9', 'TA Job Requirement Assigned', 'App\\Models\\TaWorkAssignment', '1', 'Assigned job \'Senior Python Developer\' to TA Employee Priya Patel', '127.0.0.1', 'Symfony', '2026-08-16 17:31:59', '2026-08-16 17:31:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('41', '3', 'TA Work Updated by Employee', 'App\\Models\\TaWorkAssignment', '1', 'Updated status to In Progress & sourced 8 profiles for \'Senior Python Developer\'', '127.0.0.1', 'Symfony', '2026-08-16 17:31:59', '2026-08-16 17:31:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('42', '9', 'TA Work Reviewed by Lead', 'App\\Models\\TaWorkAssignment', '1', 'Updated status to Done and added notes for \'Senior Python Developer\'', '127.0.0.1', 'Symfony', '2026-08-16 17:31:59', '2026-08-16 17:31:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('43', '3', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:32:33', '2026-08-16 17:32:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('44', '9', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:32:42', '2026-08-16 17:32:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('45', '9', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:34:12', '2026-08-16 17:34:12');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('46', '3', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:36:11', '2026-08-16 17:36:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('47', '3', 'Logout', NULL, NULL, 'User logged out', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:36:18', '2026-08-16 17:36:18');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('48', '1', 'Login', NULL, NULL, 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:36:26', '2026-08-16 17:36:26');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('49', '1', 'Candidate Edited Resume Uploaded', 'App\\Models\\Candidate', '2', 'Uploaded edited copy resume for candidate \'Amit Test Candidate\'', '127.0.0.1', 'Symfony', '2026-08-16 17:50:45', '2026-08-16 17:50:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `target_type`, `target_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('50', '1', 'Candidate Edited Resume Uploaded', 'App\\Models\\Candidate', '2', 'Uploaded edited copy resume for candidate \'Amit Test Candidate\'', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-16 17:53:08', '2026-08-16 17:53:08');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
