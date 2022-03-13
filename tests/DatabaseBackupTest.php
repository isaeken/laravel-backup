<?php

namespace IsaEken\LaravelBackup\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use IsaEken\LaravelBackup\Backup;
use IsaEken\LaravelBackup\Services\DatabaseService;
use Orchestra\Testbench\TestCase;

class DatabaseBackupTest extends TestCase
{
    use RefreshDatabase;

    protected function defineEnvironment($app)
    {
        if (file_exists(database_path('testbench.sqlite'))) {
            unlink(database_path('testbench.sqlite'));
        }

        file_put_contents(database_path('testbench.sqlite'), '');

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => database_path('testbench.sqlite'),
            'prefix' => 'tbl_',
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        Schema::create('testing', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        DB::table('testing')->insert(['name' => 'Test 1']);
        DB::table('testing')->insert(['name' => 'Test 2']);
        DB::table('testing')->insert(['name' => 'Test 3']);
        DB::table('testing')->insert(['name' => 'Test 4']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function testSqliteDatabaseBackups()
    {
        $backup = new Backup();
        $backup->addBackupService(new DatabaseService());
        $backup->addBackupStorage(Storage::disk('local'), 'local');
        $backup->run();

        $this->assertCount(1, Storage::disk('local')->files('.', true));
    }
}
