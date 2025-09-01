<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DiagnoseUploadIssues extends Command
{
    protected $signature = 'upload:diagnose';
    protected $description = 'Diagnose file upload issues in production';

    public function handle()
    {
        $this->info('🔍 Diagnosing Upload Issues...');
        $this->newLine();

        // Check PHP configuration
        $this->checkPhpConfiguration();
        
        // Check storage directories
        $this->checkStorageDirectories();
        
        // Check file permissions
        $this->checkFilePermissions();
        
        // Check disk space
        $this->checkDiskSpace();
        
        // Check web server configuration
        $this->checkWebServerConfig();
        
        // Test file operations
        $this->testFileOperations();

        $this->newLine();
        $this->info('✅ Diagnosis complete!');
    }

    private function checkPhpConfiguration()
    {
        $this->info('📋 PHP Configuration:');
        
        $uploadMaxFilesize = ini_get('upload_max_filesize');
        $postMaxSize = ini_get('post_max_size');
        $maxExecutionTime = ini_get('max_execution_time');
        $maxInputTime = ini_get('max_input_time');
        $memoryLimit = ini_get('memory_limit');
        $fileUploads = ini_get('file_uploads') ? 'Enabled' : 'Disabled';
        $maxFileUploads = ini_get('max_file_uploads');

        $this->table(['Setting', 'Value'], [
            ['upload_max_filesize', $uploadMaxFilesize],
            ['post_max_size', $postMaxSize],
            ['max_execution_time', $maxExecutionTime . ' seconds'],
            ['max_input_time', $maxInputTime . ' seconds'],
            ['memory_limit', $memoryLimit],
            ['file_uploads', $fileUploads],
            ['max_file_uploads', $maxFileUploads],
        ]);

        // Check for potential issues
        $uploadBytes = $this->parseBytes($uploadMaxFilesize);
        $postBytes = $this->parseBytes($postMaxSize);
        
        if ($uploadBytes > $postBytes) {
            $this->warn("⚠️  upload_max_filesize ($uploadMaxFilesize) is larger than post_max_size ($postMaxSize)");
        }
        
        if ($uploadBytes < 52428800) { // 50MB
            $this->warn("⚠️  upload_max_filesize ($uploadMaxFilesize) is less than 50MB - may cause issues with large files");
        }

        $this->newLine();
    }

    private function checkStorageDirectories()
    {
        $this->info('📁 Storage Directories:');
        
        $directories = [
            'storage/app' => storage_path('app'),
            'storage/app/public' => storage_path('app/public'),
            'storage/app/public/phase-documents' => storage_path('app/public/phase-documents'),
            'storage/app/temp' => storage_path('app/temp'),
            'public/storage' => public_path('storage'),
        ];

        foreach ($directories as $name => $path) {
            $exists = File::exists($path);
            $writable = $exists ? File::isWritable($path) : false;
            $readable = $exists ? File::isReadable($path) : false;
            
            $status = $exists ? '✅ Exists' : '❌ Missing';
            if ($exists) {
                $status .= $writable ? ', ✅ Writable' : ', ❌ Not Writable';
                $status .= $readable ? ', ✅ Readable' : ', ❌ Not Readable';
            }
            
            $this->line("$name: $status");
            
            if (!$exists) {
                $this->warn("  → Directory missing: $path");
            } elseif (!$writable) {
                $this->warn("  → Directory not writable: $path");
            }
        }

        // Check if storage link exists
        $storageLink = public_path('storage');
        if (!File::exists($storageLink)) {
            $this->warn("⚠️  Storage symlink missing. Run: php artisan storage:link");
        } elseif (!File::isLink($storageLink)) {
            $this->warn("⚠️  public/storage exists but is not a symlink");
        }

        $this->newLine();
    }

    private function checkFilePermissions()
    {
        $this->info('🔐 File Permissions:');
        
        $paths = [
            storage_path('app'),
            storage_path('app/public'),
            storage_path('logs'),
            public_path('storage'),
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                $owner = function_exists('posix_getpwuid') ? posix_getpwuid(fileowner($path))['name'] : 'unknown';
                $group = function_exists('posix_getgrgid') ? posix_getgrgid(filegroup($path))['name'] : 'unknown';
                
                $this->line("$path: $perms ($owner:$group)");
                
                // Check if permissions are appropriate
                if (!File::isWritable($path)) {
                    $this->warn("  → Not writable by web server");
                }
            } else {
                $this->error("$path: Does not exist");
            }
        }

        $this->newLine();
    }

    private function checkDiskSpace()
    {
        $this->info('💾 Disk Space:');
        
        $storagePath = storage_path();
        $freeBytes = disk_free_space($storagePath);
        $totalBytes = disk_total_space($storagePath);
        
        if ($freeBytes !== false && $totalBytes !== false) {
            $freeGB = round($freeBytes / 1024 / 1024 / 1024, 2);
            $totalGB = round($totalBytes / 1024 / 1024 / 1024, 2);
            $usedPercent = round((($totalBytes - $freeBytes) / $totalBytes) * 100, 1);
            
            $this->line("Free space: {$freeGB}GB / {$totalGB}GB ({$usedPercent}% used)");
            
            if ($freeGB < 1) {
                $this->warn("⚠️  Low disk space: {$freeGB}GB remaining");
            }
        } else {
            $this->warn("Could not determine disk space");
        }

        $this->newLine();
    }

    private function checkWebServerConfig()
    {
        $this->info('🌐 Web Server Configuration:');
        
        // Check if we're running under a web server
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
        $this->line("Server: $serverSoftware");
        
        // Check for common web server upload limits
        if (function_exists('apache_get_modules')) {
            $modules = apache_get_modules();
            $this->line("Apache modules loaded: " . count($modules));
            
            if (in_array('mod_security', $modules)) {
                $this->warn("⚠️  mod_security detected - may block large uploads");
            }
        }

        // Check for Nginx
        if (strpos(strtolower($serverSoftware), 'nginx') !== false) {
            $this->warn("⚠️  Nginx detected - check client_max_body_size setting");
        }

        $this->newLine();
    }

    private function testFileOperations()
    {
        $this->info('🧪 Testing File Operations:');
        
        try {
            // Test creating a directory
            $testDir = 'phase-documents/test-' . time();
            Storage::disk('public')->makeDirectory($testDir);
            $this->line("✅ Directory creation: Success");
            
            // Test file upload simulation
            $testContent = 'Test file content - ' . now();
            $testFile = $testDir . '/test-file.txt';
            Storage::disk('public')->put($testFile, $testContent);
            $this->line("✅ File creation: Success");
            
            // Test file reading
            $readContent = Storage::disk('public')->get($testFile);
            if ($readContent === $testContent) {
                $this->line("✅ File reading: Success");
            } else {
                $this->error("❌ File reading: Failed - content mismatch");
            }
            
            // Test file deletion
            Storage::disk('public')->delete($testFile);
            Storage::disk('public')->deleteDirectory($testDir);
            $this->line("✅ File/Directory deletion: Success");
            
        } catch (\Exception $e) {
            $this->error("❌ File operations failed: " . $e->getMessage());
        }

        $this->newLine();
    }

    private function parseBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int) $val;
        
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        
        return $val;
    }
}