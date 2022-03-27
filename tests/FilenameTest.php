<?php

namespace IsaEken\LaravelBackup\Tests;

use IsaEken\LaravelBackup\Filename;
use IsaEken\LaravelBackup\Services\DatabaseService;
use Orchestra\Testbench\TestCase;

class FilenameTest extends TestCase
{
    public function testFilenameGenerator()
    {
        $actual = "backup_laravel_backup_database_".date("Y_m_d_H_i_s").".zip";
        $filename = new Filename('test', 'zip', new DatabaseService(), 'local');
        $this->assertEquals($filename->value(), $actual);
    }

    public function testFilenameGenerateWithCamelCase()
    {
        $actual = "backupLaravelBackupDatabase".date("YmdHis").".zip";
        $filename = new Filename('test', 'zip', new DatabaseService(), 'local');
        $filename->camelCase = true;
        $this->assertEquals($filename->value(), $actual);
    }

    public function testWithoutExtension()
    {
        $actual = "backup_laravel_backup_database_".date("Y_m_d_H_i_s");
        $filename = new Filename('test', 'zip', new DatabaseService(), 'local');
        $filename->addExtension = false;
        $this->assertEquals($filename->value(), $actual);
    }

    public function testCustomPattern()
    {
        config()->set('backup.filename_pattern', 'just Testing_:extension:_:filename:');
        $actual = 'just_testing_zip_test.zip';
        $filename = new Filename('test', 'zip', new DatabaseService(), 'local');
        $this->assertEquals($filename->value(), $actual);
    }

    public function testReplaces()
    {
        $replaces = [
            'filename' => 'just_testing',
            'extension' => 'zip',
            'service.name' => 'database',
            'disk' => 'local',
            'datetime' => date('Y_m_d_H_i_s'),
            'date' => date('Y_m_d'),
            'time' => date('H_i_s'),
        ];

        foreach ($replaces as $key => $value) {
            config()->set('backup.filename_pattern', ":$key:");
            $filename = new Filename('Just Testing', 'zip', new DatabaseService(), 'local');
            $this->assertEquals($filename->value(), $value.'.zip');
        }
    }
}
