<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    public function index()
    {
        $backups = $this->getBackupFiles();
        return view('control-panel.backup', compact('backups'));
    }

    public function createBackup()
    {
        try {
            $backupDir = storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupDir . '/' . $filename;

            // ✅ Backup with data
            $result = $this->backupDatabaseUsingLaravel($filepath);

            if ($result['success'] && file_exists($filepath) && filesize($filepath) > 0) {
                $backupInfo = [
                    'filename' => $filename,
                    'size' => filesize($filepath),
                    'created_at' => now()->toDateTimeString(),
                    'tables' => $result['tables'],
                    'total_rows' => $result['total_rows'],
                ];
                $this->saveBackupInfo($backupInfo);

                return response()->json([
                    'success' => true,
                    'message' => "Backup created successfully! ({$result['total_rows']} rows from {$result['tables']} tables)",
                    'filename' => $filename,
                    'size' => $this->formatSize(filesize($filepath)),
                    'tables' => $result['tables'],
                    'total_rows' => $result['total_rows']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create backup! ' . ($result['error'] ?? '')
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Backup database with FULL DATA using Laravel's DB
     */
    private function backupDatabaseUsingLaravel($filepath)
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $databaseName = config('database.connections.mysql.database');
            
            $tableNames = [];
            foreach ($tables as $table) {
                $tableNames[] = reset($table);
            }

            $sql = "-- ===========================================\n";
            $sql .= "-- Database: " . $databaseName . "\n";
            $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $sql .= "-- Tables: " . count($tableNames) . "\n";
            $sql .= "-- ===========================================\n\n";

            $totalRows = 0;
            $tableCount = 0;

            foreach ($tableNames as $table) {
                // Get table structure
                $createTable = DB::select("SHOW CREATE TABLE `$table`");
                if (!empty($createTable)) {
                    $sql .= "-- --------------------------------------------------------\n";
                    $sql .= "-- Table: `$table`\n";
                    $sql .= "-- --------------------------------------------------------\n\n";
                    
                    $sql .= "DROP TABLE IF EXISTS `$table`;\n";
                    $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                    $tableCount++;
                }

                // ✅ GET TABLE DATA
                $rows = DB::table($table)->get();
                $rowCount = count($rows);
                $totalRows += $rowCount;

                if ($rowCount > 0) {
                    $sql .= "-- Inserting $rowCount rows into `$table`\n";
                    
                    // Get columns
                    $columns = array_keys((array) $rows[0]);
                    $columnsStr = implode('`, `', $columns);
                    
                    // Batch insert for better performance
                    $batchSize = 50;
                    $batch = [];
                    
                    foreach ($rows as $index => $row) {
                        $values = [];
                        foreach ($columns as $col) {
                            $val = $row->$col;
                            if ($val === null) {
                                $values[] = 'NULL';
                            } else {
                                // Escape special characters
                                $val = str_replace("'", "''", $val);
                                $val = str_replace("\\", "\\\\", $val);
                                $values[] = "'" . $val . "'";
                            }
                        }
                        $valuesStr = implode(', ', $values);
                        $batch[] = "(`$columnsStr`) VALUES ($valuesStr)";
                        
                        // If batch is full or last row, insert
                        if (count($batch) >= $batchSize || $index == $rowCount - 1) {
                            $sql .= "INSERT INTO `$table` \n" . implode(",\n", $batch) . ";\n";
                            $batch = [];
                        }
                    }
                    $sql .= "\n";
                } else {
                    $sql .= "-- Table `$table` is empty\n\n";
                }
            }

            $sql .= "\n-- ===========================================\n";
            $sql .= "-- Backup completed successfully\n";
            $sql .= "-- Total tables: $tableCount\n";
            $sql .= "-- Total rows: $totalRows\n";
            $sql .= "-- ===========================================\n";

            // Save to file
            file_put_contents($filepath, $sql);
            
            return [
                'success' => true,
                'tables' => $tableCount,
                'total_rows' => $totalRows,
                'file' => $filepath
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Alternative: Using mysqldump (better for large databases)
     */
    private function backupDatabaseUsingMysqldump($filepath)
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $mysqldumpPaths = [
            'mysqldump',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ];

        $command = null;
        foreach ($mysqldumpPaths as $path) {
            $testCmd = sprintf('%s --version 2>&1', $path);
            exec($testCmd, $output, $resultCode);
            if ($resultCode === 0) {
                $command = $path;
                break;
            }
        }

        if ($command) {
            // Add --complete-insert for better compatibility
            $cmd = sprintf(
                '%s --host=%s --user=%s --password=%s --complete-insert --add-drop-table %s > %s 2>&1',
                $command,
                $host,
                $username,
                $password,
                $database,
                $filepath
            );
            
            exec($cmd, $output, $resultCode);
            
            if ($resultCode === 0 && file_exists($filepath) && filesize($filepath) > 0) {
                // Count rows from backup file
                $content = file_get_contents($filepath);
                $insertCount = substr_count($content, 'INSERT INTO');
                
                return [
                    'success' => true,
                    'tables' => 'N/A',
                    'total_rows' => $insertCount,
                    'method' => 'mysqldump'
                ];
            }
        }
        
        return [
            'success' => false,
            'error' => 'mysqldump not available'
        ];
    }

    public function downloadBackup($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filepath)) {
            return response()->download($filepath, $filename, [
                'Content-Type' => 'application/sql',
            ]);
        }

        return redirect()->back()->with('error', 'Backup file not found!');
    }

    public function deleteBackup($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filepath)) {
            unlink($filepath);
            $this->removeBackupInfo($filename);
            
            return response()->json([
                'success' => true,
                'message' => 'Backup deleted successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Backup file not found!'
        ], 404);
    }

    public function viewBackup($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filepath)) {
            $content = file_get_contents($filepath);
            
            // Get stats
            $lines = explode("\n", $content);
            $insertLines = array_filter($lines, function($line) {
                return strpos($line, 'INSERT INTO') !== false;
            });
            
            return response()->json([
                'success' => true,
                'filename' => $filename,
                'size' => filesize($filepath),
                'insert_statements' => count($insertLines),
                'preview' => substr($content, 0, 2000) . '...'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Backup file not found!'
        ], 404);
    }

    private function getBackupFiles()
    {
        $backupDir = storage_path('app/backups');
        $files = [];
        
        if (file_exists($backupDir)) {
            $backupInfoFile = storage_path('app/backups/backup_info.json');
            $backupInfo = [];
            
            if (file_exists($backupInfoFile)) {
                $backupInfo = json_decode(file_get_contents($backupInfoFile), true) ?? [];
            }
            
            $scannedFiles = scandir($backupDir);
            foreach ($scannedFiles as $file) {
                if ($file !== '.' && $file !== '..' && $file !== 'backup_info.json' && strpos($file, '.sql') !== false) {
                    $filepath = $backupDir . '/' . $file;
                    $info = $backupInfo[$file] ?? [];
                    
                    $files[] = [
                        'filename' => $file,
                        'size' => $info['size'] ?? filesize($filepath),
                        'created_at' => $info['created_at'] ?? date('Y-m-d H:i:s', filectime($filepath)),
                        'size_formatted' => $this->formatSize($info['size'] ?? filesize($filepath)),
                        'tables' => $info['tables'] ?? 'N/A',
                        'total_rows' => $info['total_rows'] ?? 'N/A'
                    ];
                }
            }
            
            // Sort by date (newest first)
            usort($files, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }
        
        return $files;
    }

    private function saveBackupInfo($info)
    {
        $backupInfoFile = storage_path('app/backups/backup_info.json');
        $backupInfo = [];
        
        if (file_exists($backupInfoFile)) {
            $backupInfo = json_decode(file_get_contents($backupInfoFile), true) ?? [];
        }
        
        $backupInfo[$info['filename']] = $info;
        file_put_contents($backupInfoFile, json_encode($backupInfo, JSON_PRETTY_PRINT));
    }

    private function removeBackupInfo($filename)
    {
        $backupInfoFile = storage_path('app/backups/backup_info.json');
        
        if (file_exists($backupInfoFile)) {
            $backupInfo = json_decode(file_get_contents($backupInfoFile), true) ?? [];
            unset($backupInfo[$filename]);
            file_put_contents($backupInfoFile, json_encode($backupInfo, JSON_PRETTY_PRINT));
        }
    }

    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < 3) {
            $bytes /= 1024;
            $i++;
        }
        return number_format($bytes, 2) . ' ' . $units[$i];
    }
}