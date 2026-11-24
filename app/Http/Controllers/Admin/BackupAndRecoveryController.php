<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class BackupAndRecoveryController extends Controller
{
    private $backupFolder = 'private/OBRSCA';

    # This Works Fine
    public function index()
    {
        $backupPath = storage_path('app/' . $this->backupFolder);

        $files = glob($backupPath . '/*.zip');

        # dd($backupPath, $files);

        $backups = [];

        foreach ($files as $file) {
            $sizeInMB = round(filesize($file) / 1024 / 1024, 2);
            $backups[] = [
                'file_name' => basename($file),
                'size'      => $sizeInMB . ' MB',
                'date'      => date("F d, Y H:i A", filemtime($file)),
            ];
        }

        return view('admin.backup_and_recovery', compact('backups'));
    }

    # This Works Fine
    public function run()
    {
        try {
            $exitCode = Artisan::call('backup:run', ['--only-db' => true]);

            # dd($exitCode);

            $output = Artisan::output();

            if ($exitCode !== 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Backup failed. Details: ' . $output);
            }

            return redirect()
                ->back()
                ->with('success', 'Backup created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    # This Works Fine
    public function download($file_name)
    {
        $filePath = storage_path('app/' . $this->backupFolder . '/' . $file_name);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Backup file not found.');
        }

        return response()->download($filePath);
    }

    # This Works Fine
    public function restore($file_name)
{
    $filePath = storage_path('app/' . $this->backupFolder . '/' . $file_name);

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'Backup file not found.');
    }

    try {
        $zip = new \ZipArchive;
        if ($zip->open($filePath) === TRUE) {
            $extractPath = storage_path('app/' . $this->backupFolder . '/temp_restore');
            if (!is_dir($extractPath)) mkdir($extractPath, 0755, true);

            $zip->extractTo($extractPath);
            $zip->close();

            // Recursively find SQL files
            $sqlFiles = glob($extractPath . '/**/*.sql', GLOB_BRACE);
            if (!count($sqlFiles)) {
                return redirect()->back()->with('error', 'No SQL file found in backup.');
            }

            $sqlFile = $sqlFiles[0];

            // Connect to database
            $mysqli = new \mysqli(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));
            if ($mysqli->connect_error) {
                return redirect()->back()->with('error', 'Database connection failed: ' . $mysqli->connect_error);
            }

            // Disable foreign key checks
            $mysqli->query("SET FOREIGN_KEY_CHECKS=0;");

            $handle = fopen($sqlFile, 'r');
            if ($handle) {
                $query = '';
                while (($line = fgets($handle)) !== false) {
                    $line = trim($line);
                    if ($line === '' || str_starts_with($line, '--') || str_starts_with($line, '/*')) continue;

                    $query .= $line . ' ';
                    if (str_ends_with($line, ';')) {

                        // Skip queries touching 'sessions' or 'two_factor_codes'
                        $lowerQuery = strtolower($query);
                        if (str_contains($lowerQuery, 'sessions') || str_contains($lowerQuery, 'two_factor_codes')) {
                            $query = '';
                            continue;
                        }

                        if (!$mysqli->query($query)) {
                            fclose($handle);
                            $mysqli->query("SET FOREIGN_KEY_CHECKS=1;");
                            $mysqli->close();
                            array_map('unlink', glob($extractPath . '/*'));
                            rmdir($extractPath);
                            return redirect()->back()->with('error', 'Error executing query: ' . $mysqli->error);
                        }
                        $query = '';
                    }
                }
                fclose($handle);
            }

            // Re-enable foreign key checks
            $mysqli->query("SET FOREIGN_KEY_CHECKS=1;");
            $mysqli->close();

            // Cleanup temp folder recursively
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($extractPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $file) {
                $file->isDir() ? rmdir($file) : unlink($file);
            }
            rmdir($extractPath);

            return redirect()->back()->with('success', 'Database restored successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to open backup file.');
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error restoring database: ' . $e->getMessage());
    }
}


    # This Works Fine
    public function delete($file_name)
    {
        $filePath = storage_path('app/' . $this->backupFolder . '/' . $file_name);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Backup file not found.');
        }

        unlink($filePath);

        return redirect()
            ->back()
            ->with('success', 'Backup deleted successfully.');
    }
}
