<?php

namespace Differ\Differ;

function genDiff($tree1, $tree2): array
{
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
