<?php

namespace DbBackup;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupManager
{
    public static function createBackup()
    {
        $backupFileName = 'backup_' . date('Ymd_His') . '.sql';

        self::deleteOldBackups();

        self::executeBackup($backupFileName);

        return $backupFileName;
    }

    private static function deleteOldBackups()
    {
        $maxBackups = config('dbbackup.max_backups', 5);

        $backups = Storage::files('backups');

        if (count($backups) >= $maxBackups) {
            $oldestBackups = array_slice($backups, 0, count($backups) - $maxBackups + 1);

            Storage::delete($oldestBackups);
        }
    }

    private static function executeBackup($backupFileName)
    {
        $backupPath = storage_path('app/backups');

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0777, true);
        }

        $databaseName = DB::getDatabaseName();
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $command = "mysqldump -u{$username} -p{$password} -h{$host} {$databaseName} > {$backupPath}/{$backupFileName}";

        exec($command);
    }
}
