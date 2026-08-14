<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logs = [
            ['id' => 1, 'employee_id' => 17, 'last_seen_at' => null, 'clock_in' => '2025-08-14 22:33:29', 'clock_out' => '2025-08-15 06:35:33', 'duration_minutes' => 482, 'notes' => null, 'created_at' => '2025-08-14 22:33:29', 'updated_at' => '2025-08-15 06:35:33'],
            ['id' => 2, 'employee_id' => 17, 'last_seen_at' => null, 'clock_in' => '2025-08-15 06:35:37', 'clock_out' => '2025-08-15 07:01:19', 'duration_minutes' => 25, 'notes' => null, 'created_at' => '2025-08-15 06:35:37', 'updated_at' => '2025-08-15 07:01:19'],
            ['id' => 3, 'employee_id' => 17, 'last_seen_at' => null, 'clock_in' => '2025-08-15 07:06:40', 'clock_out' => '2025-08-15 07:41:03', 'duration_minutes' => null, 'notes' => null, 'created_at' => '2025-08-15 07:06:40', 'updated_at' => '2025-08-15 07:41:03'],
            ['id' => 4, 'employee_id' => 17, 'last_seen_at' => null, 'clock_in' => '2025-08-15 07:44:16', 'clock_out' => '2025-08-15 07:50:03', 'duration_minutes' => null, 'notes' => null, 'created_at' => '2025-08-15 07:44:16', 'updated_at' => '2025-08-15 07:50:03'],
            ['id' => 5, 'employee_id' => 17, 'last_seen_at' => null, 'clock_in' => '2025-08-15 07:51:21', 'clock_out' => '2025-08-15 07:57:11', 'duration_minutes' => null, 'notes' => null, 'created_at' => '2025-08-15 07:51:21', 'updated_at' => '2025-08-15 07:57:11'],
            ['id' => 6, 'employee_id' => 19, 'last_seen_at' => null, 'clock_in' => '2025-08-15 08:21:36', 'clock_out' => '2025-08-15 08:21:39', 'duration_minutes' => 0, 'notes' => null, 'created_at' => '2025-08-15 08:21:36', 'updated_at' => '2025-08-15 08:21:39'],
            ['id' => 7, 'employee_id' => 20, 'last_seen_at' => null, 'clock_in' => '2025-08-18 09:38:06', 'clock_out' => '2025-08-18 09:39:16', 'duration_minutes' => 1, 'notes' => null, 'created_at' => '2025-08-18 09:38:06', 'updated_at' => '2025-08-18 09:39:16'],
            ['id' => 8, 'employee_id' => 20, 'last_seen_at' => null, 'clock_in' => '2025-08-18 09:39:20', 'clock_out' => '2025-08-18 09:45:04', 'duration_minutes' => null, 'notes' => null, 'created_at' => '2025-08-18 09:39:20', 'updated_at' => '2025-08-18 09:45:04'],
            ['id' => 9, 'employee_id' => 21, 'last_seen_at' => null, 'clock_in' => '2025-08-18 10:13:49', 'clock_out' => '2025-08-18 10:14:43', 'duration_minutes' => 0, 'notes' => null, 'created_at' => '2025-08-18 10:13:49', 'updated_at' => '2025-08-18 10:14:43'],
            ['id' => 10, 'employee_id' => 21, 'last_seen_at' => null, 'clock_in' => '2025-08-18 10:14:48', 'clock_out' => '2025-08-18 10:20:04', 'duration_minutes' => null, 'notes' => null, 'created_at' => '2025-08-18 10:14:48', 'updated_at' => '2025-08-18 10:20:04'],
            ['id' => 11, 'employee_id' => 21, 'last_seen_at' => null, 'clock_in' => '2025-08-18 10:58:58', 'clock_out' => '2025-08-18 11:04:04', 'duration_minutes' => null, 'notes' => null, 'created_at' => '2025-08-18 10:58:58', 'updated_at' => '2025-08-18 11:04:04'],
            ['id' => 12, 'employee_id' => 21, 'last_seen_at' => null, 'clock_in' => '2025-08-18 11:05:21', 'clock_out' => '2025-08-18 11:11:03', 'duration_minutes' => null, 'notes' => null, 'created_at' => '2025-08-18 11:05:21', 'updated_at' => '2025-08-18 11:11:03'],
            ['id' => 13, 'employee_id' => 22, 'last_seen_at' => null, 'clock_in' => '2025-08-18 11:50:05', 'clock_out' => '2025-08-18 11:56:03', 'duration_minutes' => null, 'notes' => null, 'created_at' => '2025-08-18 11:50:05', 'updated_at' => '2025-08-18 11:56:03'],
            ['id' => 14, 'employee_id' => 22, 'last_seen_at' => null, 'clock_in' => '2025-08-18 14:44:35', 'clock_out' => '2025-08-18 14:50:04', 'duration_minutes' => null, 'notes' => null, 'created_at' => '2025-08-18 14:44:35', 'updated_at' => '2025-08-18 14:50:04'],
            ['id' => 15, 'employee_id' => 22, 'last_seen_at' => null, 'clock_in' => '2025-08-18 16:18:41', 'clock_out' => '2025-08-18 16:24:03', 'duration_minutes' => null, 'notes' => null, 'created_at' => '2025-08-18 16:18:41', 'updated_at' => '2025-08-18 16:24:03'],
        ];

        DB::table('attendance_logs')->insert($logs);
    }
}
