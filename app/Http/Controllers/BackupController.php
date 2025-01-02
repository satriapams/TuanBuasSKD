<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function backupDatabase()
    {
        try {
            // Nama file backup dengan format timestamp
            $fileName = 'backup-' . date('Y-m-d_H-i-s') . '.sql';

            // Path folder penyimpanan
            $path = storage_path('app/backups/' . $fileName);

            // Cek apakah folder backup ada, jika tidak buat foldernya
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0775, true);
            }

            // Cek password database (jika kosong, jangan tambahkan parameter password)
            $password = env('DB_PASSWORD') ? '--password=' . env('DB_PASSWORD') : '';

            // Eksekusi perintah mysqldump
            $command = sprintf(
                'mysqldump --user=%s %s --host=%s --port=%s %s > %s',
                env('DB_USERNAME'),   // Username dari .env
                $password,            // Password (bisa kosong)
                env('DB_HOST'),       // Host dari .env
                env('DB_PORT'),       // Port dari .env
                env('DB_DATABASE'),   // Nama database dari .env
                $path                 // Lokasi penyimpanan file
            );

            // Eksekusi command
            $output = null;
            $result = null;
            exec($command, $output, $result);

            // Cek hasil eksekusi
            if ($result !== 0) {
                return back()->with('error', 'Backup gagal dibuat!');
            }

            // Berikan respons untuk mengunduh file
            return response()->download($path)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function downloadBackup($fileName)
    {
        // Path lengkap file
        $filePath = storage_path('app/backups/' . $fileName);

        // Cek apakah file ada
        if (file_exists($filePath)) {
            // Respons untuk mengunduh file
            return response()->download($filePath, $fileName);
        }

        // Jika file tidak ditemukan
        return back()->with('error', 'File backup tidak ditemukan!');
    }

    public function restoreDatabase(Request $request)
    {
        try {
            // Ambil file backup dari input
            $file = $request->file('backup_file');

            // Pastikan file ada
            if (!$file) {
                return back()->with('error', 'File backup tidak ditemukan!');
            }

            // Path file yang diupload
            $path = $file->getRealPath();

            // Perintah untuk restore menggunakan mysql
            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s --port=%s %s < %s',
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                env('DB_HOST'),
                env('DB_PORT'),
                env('DB_DATABASE'),
                escapeshellarg($path)  // Escape path file untuk keamanan
            );

            // Eksekusi perintah restore
            $output = null;
            $result = null;
            exec($command, $output, $result);

            // Cek hasil eksekusi
            if ($result !== 0) {
                return back()->with('error', 'Restore database gagal!');
            }

            return back()->with('success', 'Database berhasil di-restore!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
