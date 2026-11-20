<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SetupVideoStorageCommandTest extends TestCase
{
    public function test_setup_video_storage_command_runs_successfully(): void
    {
        $this->artisan('app:setup-video')
            ->expectsOutputToContain('Penyimpanan video lokal siap digunakan')
            ->assertExitCode(0);

        $this->assertTrue(File::isDirectory(storage_path('app/private/videos')));
        $this->assertTrue(Storage::disk('local')->exists('videos/sample.mp4'));
    }
}
