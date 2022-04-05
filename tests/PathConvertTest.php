<?php

use IsaEken\LaravelBackup\Filename;
use function PHPUnit\Framework\assertEquals;

it('is replacing path separators', function () {
    $separators = ['/', '\\'];

    $x = function () use ($separators) {
        return $separators[array_rand($separators)];
    };

    foreach ($separators as $separator) {
        Filename::mockDirectorySeparator($separator);

        $patterns = [
            $x().'lorem'.$x().'ipsum'.$x().'dolor' => "{$separator}lorem{$separator}ipsum{$separator}dolor",
            'lorem'.$x().'ipsum'.$x().'dolor' => "lorem{$separator}ipsum{$separator}dolor",
            'lorem'.$x().'ipsum'.$x().$x().$x().'dolor' => "lorem{$separator}ipsum{$separator}dolor",
            'lorem'.$x().'ipsum'.$x().'dolor'.$x() => "lorem{$separator}ipsum{$separator}dolor",
        ];

        foreach ($patterns as $expected => $actual) {
            assertEquals(replacePathSeparators($expected), $actual);
        }
    }
});

it('is converts paths to zip paths', function () {
    Filename::mockDirectorySeparator('/');

    $paths = [
        '/home/test/zip/test.txt' => 'test.txt',
        '/home/test/zip/test/test.txt' => 'test/test.txt',
        '/home/test/zip/1.txt' => '1.txt',
        '/home/test/zip/2.txt' => '2.txt',
        '/home/test/zip/test/1.txt' => 'test/1.txt',
        '/home/test/zip/test/2.txt' => 'test/2.txt',
    ];

    foreach ($paths as $excepted => $actual) {
        assertEquals(convertToZipPath($excepted, '/home/test/zip'), $actual);
        assertEquals(convertToZipPath($excepted, '/home/test/zip/'), $actual);
    }

    Filename::mockDirectorySeparator('\\');

    $paths = [
        '\\home\\test\\zip\\test.txt' => 'test.txt',
        '\\home\\test\\zip\\test\\TeSt.txt' => 'test/TeSt.txt',
        '\\home\\test\\zip\\1.txt' => '1.txt',
        '\\home\\test\\zip\\2.txt' => '2.txt',
        '\\home\\test\\zip\\test\\1.txt' => 'test/1.txt',
        '\\home\\test\\zip\\TEST\\2.txt' => 'TEST/2.txt',
    ];

    foreach ($paths as $excepted => $actual) {
        assertEquals(convertToZipPath($excepted, '\\home\\test\\zip'), $actual);
        assertEquals(convertToZipPath($excepted, '\\home\\test\\zip\\'), $actual);
    }

    $paths = [
        'C:\\Users\\TestUser\\Documents\\Zip\\test.txt' => 'test.txt',
        'c:\\Users\\testUser\\docuMENTS\\zip\\test\\TeSt.txt' => 'test/TeSt.txt',
        'C:\\Users\\testuser\\dOcUmEnTs\\zIp\\1.txt' => '1.txt',
        'C:\\Users\\TeStUsEr\\DOCUMENTS\\ZIP\\2.txt' => '2.txt',
        'c:\\Users\\Testuser\\Documents\\ziP\\test\\1.txt' => 'test/1.txt',
        'c:\\Users\\testUser\\documents\\Zip\\TEST\\2.txt' => 'TEST/2.txt',
    ];

    foreach ($paths as $excepted => $actual) {
        assertEquals(convertToZipPath($excepted, 'C:\\Users\\TestUser\\Documents\\Zip'), $actual);
        assertEquals(convertToZipPath($excepted, 'C:\\Users\\TestUser\\Documents\\Zip\\'), $actual);
    }
});
