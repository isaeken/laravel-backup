<?php

namespace IsaEken\LaravelBackup\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use IsaEken\LaravelBackup\Models\Backup;
use Orchestra\Testbench\TestCase;

class BackupModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function testCreate()
    {
        $beforeCount = Backup::all()->count();

        $attributes = [
            'filename' => 'TestBackup',
            'disk' => 'local',
            'created_at' => Carbon::make('-5 minutes'),
            'size' => 1024,
        ];

        $model = Backup::create($attributes)->getAttributes();
        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $model[$key]);
        }

        $this->assertCount($beforeCount + 1, Backup::all());
    }
}
