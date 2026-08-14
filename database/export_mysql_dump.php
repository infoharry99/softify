<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = [
    'users', 'password_reset_tokens', 'failed_jobs', 'personal_access_tokens',
    'roles', 'permissions', 'role_user', 'permission_role', 'permission_user',
    'employees', 'employee_profiles', 'employee_joining_details',
    'leave_types', 'employee_leave_balances', 'leave_applications',
    'attendances', 'attendance_sessions', 'attendance_breaks',
    'salary_structures', 'payrolls', 'candidates', 'finance_requirements',
    'announcements', 'notifications', 'activity_logs'
];

$sql = "-- ========================================================\n";
$sql .= "-- SalesTaletity Production MySQL Database Dump\n";
$sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$sql .= "-- Ready for Import on Live MySQL / phpMyAdmin Server\n";
$sql .= "-- ========================================================\n\n";

$sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
$sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
$sql .= "START TRANSACTION;\n";
$sql .= "SET time_zone = \"+05:30\";\n\n";

foreach ($tables as $table) {
    if (!Schema::connection('sqlite')->hasTable($table)) continue;

    $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

    // Inspect SQLite columns dynamically
    $columnsInfo = DB::connection('sqlite')->select("PRAGMA table_info(`{$table}`)");
    
    $columnDefs = [];
    $primaryKeys = [];

    foreach ($columnsInfo as $col) {
        $name = $col->name;
        $type = strtoupper($col->type);
        $notNull = $col->notnull ? 'NOT NULL' : 'DEFAULT NULL';
        $dflt = $col->dflt_value;

        // Map SQLite types to MySQL types
        if ($name === 'id' && ($type === 'INTEGER' || str_contains($type, 'INT'))) {
            $columnDefs[] = "  `{$name}` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT";
            $primaryKeys[] = "`{$name}`";
            continue;
        }

        if (str_contains($name, '_id') && ($type === 'INTEGER' || str_contains($type, 'INT'))) {
            $mysqlType = 'bigint(20) UNSIGNED';
        } elseif ($type === 'INTEGER' || $type === 'INT') {
            $mysqlType = 'int(11)';
        } elseif ($type === 'BOOLEAN' || $type === 'TINYINT(1)') {
            $mysqlType = 'tinyint(1)';
        } elseif (str_contains($type, 'NUMERIC') || str_contains($type, 'DECIMAL')) {
            $mysqlType = 'decimal(12,2)';
        } elseif ($type === 'DATE') {
            $mysqlType = 'date';
        } elseif ($type === 'DATETIME' || $type === 'TIMESTAMP') {
            $mysqlType = 'timestamp';
        } elseif (str_contains($type, 'TEXT')) {
            $mysqlType = 'text';
        } else {
            $mysqlType = 'varchar(255)';
        }

        $def = "  `{$name}` {$mysqlType} {$notNull}";

        if (!is_null($dflt)) {
            if ($dflt === 'CURRENT_TIMESTAMP') {
                $def .= " DEFAULT CURRENT_TIMESTAMP";
            } elseif (is_numeric($dflt)) {
                $def .= " DEFAULT {$dflt}";
            } else {
                $cleanDflt = trim($dflt, "'\"");
                $def .= " DEFAULT '{$cleanDflt}'";
            }
        }

        $columnDefs[] = $def;

        if ($col->pk) {
            $primaryKeys[] = "`{$name}`";
        }
    }

    if (!empty($primaryKeys)) {
        $columnDefs[] = "  PRIMARY KEY (" . implode(', ', $primaryKeys) . ")";
    }

    $sql .= "CREATE TABLE `{$table}` (\n" . implode(",\n", $columnDefs) . "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

    // Insert Data
    $rows = DB::connection('sqlite')->table($table)->get();
    if (!$rows->isEmpty()) {
        $sql .= "-- Data for table `{$table}`\n";
        foreach ($rows as $row) {
            $arr = (array)$row;
            $cols = array_keys($arr);
            $vals = array_map(function($v) {
                if (is_null($v)) return 'NULL';
                if (is_bool($v)) return $v ? 1 : 0;
                return "'" . addslashes((string)$v) . "'";
            }, array_values($arr));

            $sql .= "INSERT INTO `{$table}` (`" . implode("`, `", $cols) . "`) VALUES (" . implode(", ", $vals) . ");\n";
        }
        $sql .= "\n";
    }
}

$sql .= "SET FOREIGN_KEY_CHECKS=1;\nCOMMIT;\n";

$targetPath = __DIR__ . '/salestaletity_live_database.sql';
file_put_contents($targetPath, $sql);
echo "SUCCESS: Dynamically generated 100% column-matched MySQL dump at database/salestaletity_live_database.sql (" . strlen($sql) . " bytes)\n";
