<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function index()
    {
        $files = File::files(storage_path('app'));
        $backups = [];

        foreach ($files as $file) {
            if (str_contains($file->getFilename(), 'backup_')) {
                $backups[] = $file->getFilename();
            }
        }

        rsort($backups);

        return view('backup.index', compact('backups'));
    }

    public function run()
{
    $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $path = storage_path('app/' . $filename);

    $db   = env('DB_DATABASE');   // nilai
    $user = env('DB_USERNAME');   // root
    $pass = env('DB_PASSWORD');   // kosong

    // ✅ PATH LARAGON KAMU
    $mysqldump = '"C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe"';

    if ($pass == '') {
        $command = "{$mysqldump} -u {$user} {$db} > \"{$path}\"";
    } else {
        $command = "{$mysqldump} -u {$user} -p{$pass} {$db} > \"{$path}\"";
    }

    exec($command, $output, $result);

    if ($result !== 0 || !file_exists($path) || filesize($path) == 0) {
        return back()->with('error', 'Backup gagal. Periksa konfigurasi mysqldump.');
    }

    return back()->with('success', 'Backup berhasil dibuat.');
}

    public function download($file)
    {
        $path = storage_path('app/' . $file);
        return response()->download($path);
    }

    public function delete($file)
    {
        File::delete(storage_path('app/' . $file));
        return back()->with('success', 'Backup dihapus.');
    }
}