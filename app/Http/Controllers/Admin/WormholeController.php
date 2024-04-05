<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;

class WormholeController extends Controller
{
    //
    public function backupAndWipe()
    {
        // Database connection parameters
        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        // Temporary backup directory
        $backupDir = '/tmp';

        // MySQL dump command
        $dumpFile = "$backupDir/$database.sql";
        $dumpCommand = "mysqldump --host=$host --user=$username --password=$password $database > $dumpFile";

        // Execute backup command
        $process = Process::fromShellCommandline($dumpCommand);
        $process->run();

        if ($process->isSuccessful()) {
            // Wipe out all data from tables
           /* $tables = DB::select("SHOW TABLES");

            foreach ($tables as $table) {
                $tableName = reset($table);
                DB::table($tableName)->truncate();
            } */

            return response()->download($dumpFile)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'Database backup failed.'], 500);
        }
    }

}
