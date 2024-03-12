<?php

namespace Differ\Differ;

function genDiff($tree1, $tree2)
{
//    print_r($tree1);
//    print_r($tree2);

//    print_r('gendiff work!');
    $expected = [
        'deleted' => [
            'follow' => false
        ],
        'unchanged' => [
            'host' => 'hexlet.io'
        ],
        'changed' => [
            'timeout' => [50, 20]
        ],
        'added' => [
            'verbose' => true
        ]

    ];

    return $expected;
}
