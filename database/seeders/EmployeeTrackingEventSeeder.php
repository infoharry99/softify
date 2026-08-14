<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeTrackingEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employee_tracking_events')->insert([
            ['id' => 13, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:13:40', 'created_at' => '2025-08-06 10:13:42', 'updated_at' => '2025-08-06 10:13:42'],
            ['id' => 14, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:13:42', 'created_at' => '2025-08-06 10:13:43', 'updated_at' => '2025-08-06 10:13:43'],
            ['id' => 15, 'tracking_id' => 14, 'type' => 'internet_off', 'event_time' => '2025-08-06 10:14:06', 'created_at' => '2025-08-06 10:14:21', 'updated_at' => '2025-08-06 10:14:21'],
            ['id' => 16, 'tracking_id' => 14, 'type' => 'internet_on', 'event_time' => '2025-08-06 10:14:22', 'created_at' => '2025-08-06 10:14:24', 'updated_at' => '2025-08-06 10:14:24'],
            ['id' => 17, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:14:43', 'created_at' => '2025-08-06 10:14:44', 'updated_at' => '2025-08-06 10:14:44'],
            ['id' => 18, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:15:10', 'created_at' => '2025-08-06 10:15:11', 'updated_at' => '2025-08-06 10:15:11'],
            ['id' => 19, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:16:30', 'created_at' => '2025-08-06 10:16:31', 'updated_at' => '2025-08-06 10:16:31'],
            ['id' => 20, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:16:31', 'created_at' => '2025-08-06 10:16:32', 'updated_at' => '2025-08-06 10:16:32'],
            ['id' => 21, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:19:11', 'created_at' => '2025-08-06 10:19:15', 'updated_at' => '2025-08-06 10:19:15'],
            ['id' => 22, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:19:14', 'created_at' => '2025-08-06 10:19:16', 'updated_at' => '2025-08-06 10:19:16'],
            ['id' => 23, 'tracking_id' => 14, 'type' => 'internet_on', 'event_time' => '2025-08-06 10:20:37', 'created_at' => '2025-08-06 10:20:39', 'updated_at' => '2025-08-06 10:20:39'],
            ['id' => 24, 'tracking_id' => 14, 'type' => 'internet_on', 'event_time' => '2025-08-06 10:20:37', 'created_at' => '2025-08-06 10:20:39', 'updated_at' => '2025-08-06 10:20:39'],
            ['id' => 25, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:20:38', 'created_at' => '2025-08-06 10:20:39', 'updated_at' => '2025-08-06 10:20:39'],
            ['id' => 26, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:20:39', 'created_at' => '2025-08-06 10:20:40', 'updated_at' => '2025-08-06 10:20:40'],
            ['id' => 27, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:20:42', 'created_at' => '2025-08-06 10:20:43', 'updated_at' => '2025-08-06 10:20:43'],
            ['id' => 28, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:20:42', 'created_at' => '2025-08-06 10:20:43', 'updated_at' => '2025-08-06 10:20:43'],
            ['id' => 29, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:20:45', 'created_at' => '2025-08-06 10:20:45', 'updated_at' => '2025-08-06 10:20:45'],
            ['id' => 30, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:20:45', 'created_at' => '2025-08-06 10:20:46', 'updated_at' => '2025-08-06 10:20:46'],
            ['id' => 31, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:20:46', 'created_at' => '2025-08-06 10:20:47', 'updated_at' => '2025-08-06 10:20:47'],
            ['id' => 32, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:20:46', 'created_at' => '2025-08-06 10:20:47', 'updated_at' => '2025-08-06 10:20:47'],
            ['id' => 33, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:20:50', 'created_at' => '2025-08-06 10:20:50', 'updated_at' => '2025-08-06 10:20:50'],
            ['id' => 34, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:20:52', 'created_at' => '2025-08-06 10:20:53', 'updated_at' => '2025-08-06 10:20:53'],
            ['id' => 35, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:21:24', 'created_at' => '2025-08-06 10:21:25', 'updated_at' => '2025-08-06 10:21:25'],
            ['id' => 36, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:21:25', 'created_at' => '2025-08-06 10:21:26', 'updated_at' => '2025-08-06 10:21:26'],
            ['id' => 37, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:22:19', 'created_at' => '2025-08-06 10:22:22', 'updated_at' => '2025-08-06 10:22:22'],
            ['id' => 38, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:22:31', 'created_at' => '2025-08-06 10:22:32', 'updated_at' => '2025-08-06 10:22:32'],
            ['id' => 39, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:22:31', 'created_at' => '2025-08-06 10:22:32', 'updated_at' => '2025-08-06 10:22:32'],
            ['id' => 40, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 10:22:34', 'created_at' => '2025-08-06 10:22:40', 'updated_at' => '2025-08-06 10:22:40'],
            ['id' => 41, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 10:22:39', 'created_at' => '2025-08-06 10:22:40', 'updated_at' => '2025-08-06 10:22:40'],
            ['id' => 42, 'tracking_id' => 14, 'type' => 'internet_off', 'event_time' => '2025-08-06 10:54:44', 'created_at' => '2025-08-06 10:54:48', 'updated_at' => '2025-08-06 10:54:48'],
            ['id' => 43, 'tracking_id' => 14, 'type' => 'internet_off', 'event_time' => '2025-08-06 10:54:44', 'created_at' => '2025-08-06 10:54:48', 'updated_at' => '2025-08-06 10:54:48'],
            ['id' => 44, 'tracking_id' => 14, 'type' => 'internet_on', 'event_time' => '2025-08-06 10:54:49', 'created_at' => '2025-08-06 10:54:50', 'updated_at' => '2025-08-06 10:54:50'],
            ['id' => 45, 'tracking_id' => 14, 'type' => 'internet_on', 'event_time' => '2025-08-06 10:54:49', 'created_at' => '2025-08-06 10:54:50', 'updated_at' => '2025-08-06 10:54:50'],
            ['id' => 46, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 11:07:59', 'created_at' => '2025-08-06 11:08:01', 'updated_at' => '2025-08-06 11:08:01'],
            ['id' => 47, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 11:08:01', 'created_at' => '2025-08-06 11:08:02', 'updated_at' => '2025-08-06 11:08:02'],
            ['id' => 48, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 11:08:11', 'created_at' => '2025-08-06 11:08:12', 'updated_at' => '2025-08-06 11:08:12'],
            ['id' => 49, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 11:08:12', 'created_at' => '2025-08-06 11:08:13', 'updated_at' => '2025-08-06 11:08:13'],
            ['id' => 50, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 11:10:53', 'created_at' => '2025-08-06 11:10:56', 'updated_at' => '2025-08-06 11:10:56'],
            ['id' => 51, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 11:10:56', 'created_at' => '2025-08-06 11:10:58', 'updated_at' => '2025-08-06 11:10:58'],
            ['id' => 52, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 11:12:35', 'created_at' => '2025-08-06 11:12:36', 'updated_at' => '2025-08-06 11:12:36'],
            ['id' => 53, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 11:12:36', 'created_at' => '2025-08-06 11:12:37', 'updated_at' => '2025-08-06 11:12:37'],
            ['id' => 54, 'tracking_id' => 14, 'type' => 'internet_on', 'event_time' => '2025-08-06 11:15:07', 'created_at' => '2025-08-06 11:15:09', 'updated_at' => '2025-08-06 11:15:09'],
            ['id' => 55, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 11:15:37', 'created_at' => '2025-08-06 11:15:39', 'updated_at' => '2025-08-06 11:15:39'],
            ['id' => 56, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 11:15:39', 'created_at' => '2025-08-06 11:15:41', 'updated_at' => '2025-08-06 11:15:41'],
            ['id' => 57, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 11:18:15', 'created_at' => '2025-08-06 11:18:22', 'updated_at' => '2025-08-06 11:18:22'],
            ['id' => 58, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 11:18:21', 'created_at' => '2025-08-06 11:18:22', 'updated_at' => '2025-08-06 11:18:22'],
            ['id' => 59, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 11:24:04', 'created_at' => '2025-08-06 11:24:07', 'updated_at' => '2025-08-06 11:24:07'],
            ['id' => 60, 'tracking_id' => 14, 'type' => 'system_on', 'event_time' => '2025-08-06 11:24:06', 'created_at' => '2025-08-06 11:24:07', 'updated_at' => '2025-08-06 11:24:07'],
            ['id' => 61, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 11:24:14', 'created_at' => '2025-08-06 11:24:15', 'updated_at' => '2025-08-06 11:24:15'],
            ['id' => 62, 'tracking_id' => 14, 'type' => 'system_off', 'event_time' => '2025-08-06 12:32:39', 'created_at' => '2025-08-06 12:32:42', 'updated_at' => '2025-08-06 12:32:42'],
            ['id' => 63, 'tracking_id' => 15, 'type' => 'system_on', 'event_time' => '2025-08-08 10:37:05', 'created_at' => '2025-08-08 10:37:08', 'updated_at' => '2025-08-08 10:37:08'],
            ['id' => 64, 'tracking_id' => 15, 'type' => 'system_off', 'event_time' => '2025-08-08 10:37:07', 'created_at' => '2025-08-08 10:37:09', 'updated_at' => '2025-08-08 10:37:09'],
            ['id' => 65, 'tracking_id' => 16, 'type' => 'system_on', 'event_time' => '2025-08-11 09:55:29', 'created_at' => '2025-08-11 09:55:31', 'updated_at' => '2025-08-11 09:55:31'],
            ['id' => 66, 'tracking_id' => 16, 'type' => 'system_off', 'event_time' => '2025-08-11 09:59:28', 'created_at' => '2025-08-11 09:59:31', 'updated_at' => '2025-08-11 09:59:31'],
        ]);
    }
}
