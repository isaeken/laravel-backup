<?php

namespace IsaEken\LaravelBackup\Tests;

use IsaEken\LaravelBackup\Compressors\ZipCompressor;
use IsaEken\LaravelBackup\Filename;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use ZipArchive;

it('is compressing files', function () {
    Filename::mockDirectorySeparator(DIRECTORY_SEPARATOR);

    $directory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'laravel-backup-test-'.rand(0, 10000);
    @mkdir($directory);

    $files = [
        'test1.txt' => 'Hello World 1',
        'test2.txt' => (string) time(),
        'test3.txt' => '❤️❤️❤️',
    ];

    foreach ($files as $file => $contents) {
        @file_put_contents($directory.DIRECTORY_SEPARATOR.$file, $contents);
    }

    $compressor = new ZipCompressor();
    $compressor->setSource($directory);
    $compressor->setDestination($directory);
    $compressor->run();

    $zip = new ZipArchive();
    $zip->open($compressor->getDestination());

    assertCount($zip->numFiles, $files);

    for ($i = 0; $i < $zip->numFiles; $i++) {
        $stat = $zip->statIndex($i);
        $filename = basename($stat['name']);
        assertArrayHasKey($filename, $files);
        assertContains($zip->getFromName($filename), $files, sprintf(
            'Failed asserting that an zip file contains "%s" (filename: "%s")',
            $zip->getFromName($filename),
            $filename,
        ));
    }

    @rmdir($directory);
})->skip(str_contains(PHP_OS, 'WIN'), 'ZIP compression tests is not working on Windows Servers currently.');

it('is compressing nested', function () {
    Filename::mockDirectorySeparator(DIRECTORY_SEPARATOR);

    $directory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'laravel-backup-test-'.rand(0, 10000);
    @mkdir($directory);

    $directories = [
        'dir1' => [
            'dir1' => ['dir1', 'dir2', 'dir3'],
            'dir2' => ['dir1', 'dir2', 'dir3'],
            'dir3' => [],
            time() => [time()],
        ],
        'dir2' => [
            'dir1' => ['dir1', 'dir2', 'dir3'],
            'dir2' => ['dir1', 'dir2', 'dir3'],
            'dir3' => [],
            time() => [time()],
        ],
        'dir3' => [
            'dir1' => ['dir1', 'dir2', 'dir3'],
            'dir2' => ['dir1', 'dir2', 'dir3'],
            'dir3' => [],
            time() => [time()],
        ],
    ];

    function createDirectories(string $path, array $directories)
    {
        foreach ($directories as $a => $b) {
            if (is_array($b)) {
                @mkdir($path.DIRECTORY_SEPARATOR.$a);
                createDirectories($path.DIRECTORY_SEPARATOR.$a, $b);
            } else {
                @mkdir($path.DIRECTORY_SEPARATOR.$b);
                @file_put_contents($path.DIRECTORY_SEPARATOR.$b.DIRECTORY_SEPARATOR.'test.txt', 'Hello World');
            }
        }
    }

    createDirectories($directory, $directories);

    $compressor = new ZipCompressor();
    $compressor->setSource($directory);
    $compressor->setDestination($directory);
    $compressor->run();

    $zip = new ZipArchive();
    $zip->open($compressor->getDestination());

    for ($a = 1; $a < 4; $a++) {
        for ($b = 1; $b < 3; $b++) {
            for ($c = 1; $c < 4; $c++) {
                assertEquals(
                    'Hello World',
                    $zip->getFromName(convertToZipPath("dir$a/dir$b/dir$c/test.txt", $compressor->getSource()))
                );
            }
        }
    }

    @rmdir($directory);
})->skip(str_contains(PHP_OS, 'WIN'), 'ZIP compression tests is not working on Windows Servers currently.');

it('is compressing with password', function () {
    Filename::mockDirectorySeparator(DIRECTORY_SEPARATOR);

    $directory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'laravel-backup-test-'.rand(0, 10000);
    @mkdir($directory);
    $password = time().'abc';

    file_put_contents($directory.DIRECTORY_SEPARATOR.'test.txt', 'Hello World');

    $compressor = new ZipCompressor();
    $compressor->setSource($directory);
    $compressor->setDestination($directory);
    $compressor->setPassword($password);
    $compressor->run();

    $zip = new ZipArchive();
    $zip->open($compressor->getDestination());
    $zip->setPassword($password);

    assertEquals(259, $zip->statName('test.txt')['encryption_method']);
    assertEquals('Hello World', $zip->getFromName('test.txt'));

    @rmdir($directory);
})->skip(str_contains(PHP_OS, 'WIN'), 'ZIP compression tests is not working on Windows Servers currently.');
