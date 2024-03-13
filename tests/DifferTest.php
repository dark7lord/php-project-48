<?php

namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;
use Differ\Differ;

class DifferTest extends TestCase
{
    public function testGenDiff()
    {
        $tree1 = [
            'host' => 'hexlet.io',
            'timeout' => 50,
            'proxy' => '123.234.53.22',
            'follow' => false
        ];
        $tree2 = [
            'timeout' => 20,
            'verbose' => true,
            'host' => 'hexlet.io'
        ];

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

//        $expected
        $actual = Differ\genDiff($tree1, $tree2);
//        $this->assertIsArray($actual);

        $this->assertEquals($actual, $expected);
    }
}
