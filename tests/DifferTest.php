<?php

namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff()
    {
//        $tree1 = [
//            'host' => 'hexlet.io',
//            'timeout' => 50,
//            'proxy' => '123.234.53.22',
//            'follow' => false
//        ];
//        $tree2 = [
//            'timeout' => 20,
//            'verbose' => true,
//            'host' => 'hexlet.io'
//        ];

        $expected = <<<'EOT'
{
   - follow: false
     host: hexlet.io
   - proxy: 123.234.53.22
   - timeout: 50
   + timeout: 20
   + verbose: true
}
EOT;
        $filename1 = 'files/file1.json';
        $filename2 = 'files/file2.json';

        $actual = genDiff($filename1, $filename2);
        $this->assertEquals($actual, $expected);
    }

    public function testGenDiffYaml()
    {
        $expected = <<<'EOT'
{
   - follow: false
     host: hexlet.io
   - proxy: 123.234.53.22
   - timeout: 50
   + timeout: 20
   + verbose: true
}
EOT;
        $filename1 = 'files/file1.json';
        $filename2 = 'files/file2.yml';

        $actual = genDiff($filename1, $filename2);
        $this->assertEquals($actual, $expected);
    }
}
