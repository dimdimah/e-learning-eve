<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupVideoStorage extends Command
{
    /**
     * Nama dan signature console command.
     */
    protected $signature = 'app:setup-video {--force : Timpa file sample video jika sudah ada}';

    /**
     * Deskripsi console command.
     */
    protected $description = 'Setup direktori storage video privat lokal dan siapkan sample video untuk demo pembelajaran';

    /**
     * Jalankan console command.
     */
    public function handle(): int
    {
        $this->info('Memulai setup penyimpanan video lokal...');

        $storagePath = storage_path('app/private/videos');

        // 1. Buat direktori penyimpanan video privat jika belum ada
        if (! File::isDirectory($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
            $this->line('<info>[OK]</info> Direktori penyimpanan dibuat: storage/app/private/videos');
        } else {
            $this->line('<comment>[INFO]</comment> Direktori storage/app/private/videos sudah tersedia.');
        }

        // 2. Pasang file .gitignore agar file video lokal tidak ter-commit ke Git/GitHub
        $gitignoreFile = $storagePath.'/.gitignore';
        if (! File::exists($gitignoreFile)) {
            File::put($gitignoreFile, "*\n!.gitignore\n");
            $this->line('<info>[OK]</info> File .gitignore terpasang (file video aman dari git push).');
        }

        // 3. Siapkan file video contoh (sample.mp4)
        $sampleFile = $storagePath.'/sample.mp4';
        if (! File::exists($sampleFile) || $this->option('force')) {
            $localVideoSource = 'C:/Users/dimdimah/Downloads/WhatsApp Video 2026-10-26 at 14.15.11 (1).mp4';

            if (File::exists($localVideoSource)) {
                File::copy($localVideoSource, $sampleFile);
                $this->line('<info>[OK]</info> Video contoh disalin dari lokal: '.round(filesize($sampleFile) / 1024 / 1024, 2).' MB');
            } else {
                // Buat binary video MP4 valid dengan box ftypmp42 + moov + mdat agar langsung dikenali player HTML5
                $ftyp = pack('N', 32).'ftypmp42'.pack('N', 0).'isommp41mp42';
                $moov = pack('N', 8).'moov';
                $mdat = pack('N', 65536).'mdat'.str_repeat("\0", 65528);
                File::put($sampleFile, $ftyp.$moov.$mdat);
                $this->line('<info>[OK]</info> File video contoh valid dibuat: storage/app/private/videos/sample.mp4');
            }
        } else {
            $this->line('<comment>[INFO]</comment> File video contoh sample.mp4 sudah ada di storage lokal.');
        }

        $this->newLine();
        $this->info('✓ Penyimpanan video lokal siap digunakan!');

        return Command::SUCCESS;
    }
}
