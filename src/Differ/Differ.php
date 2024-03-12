<?php

namespace Differ\Differ;

function printDifferecne($tree, $offset = 2)
{
    $padding = str_repeat(' ', $offset);
    $lines = array_map(function ($property) use ($padding) {
        [
            'key' => $key,
            'type' => $type,
            'value' => $value
        ] = $property;

        if ($type === 'unchanged') {
            return "$padding   $key: $value";
        }
        if ($type === 'added') {
            return "$padding + $key: $value";
        }
        if ($type === 'deleted') {
            return "$padding - $key: $value";
        }
        if ($type === 'changed') {
            [
                'oldValue' => $oldValue,
                'newValue' => $newValue
            ] = $value;

            $oldString = "$padding - $key: $oldValue";
            $newString = "$padding + $key: $newValue";

            return "$oldString\n$newString";
        }

        return '';

    }, $tree);

    $result = implode("\n", ['{', ...$lines, '}']);
    print_r($result);
    return $result;
}

function genDiff($tree1, $tree2)
{
    $keys1 = array_keys($tree1);
    $keys2 = array_keys($tree2);

    $deletedKeys = array_diff($keys1, $keys2);
    $addedKeys = array_diff($keys2, $keys1);
    $allKeys = array_unique(array_merge($keys1, $keys2));
    sort($allKeys);

    $resultTree = array_map(function ($key) use ($tree1, $tree2, $deletedKeys, $addedKeys) {
        if (in_array($key, $deletedKeys)) {
            $type = 'deleted';
            $value = $tree1[$key];
        } elseif (in_array($key, $addedKeys)) {
            $type = 'added';
            $value = $tree2[$key];
        } else {
            $oldValue = $tree1[$key];
            $newValue = $tree2[$key];

            if ($oldValue === $newValue) {
                $type = 'unchanged';
                $value = $tree1[$key];
            } else {
                $type = 'changed';
                $value = [
                    'oldValue' => $oldValue,
                    'newValue' => $newValue
                ];
            }
        }

        return [
            'key' => $key,
            'type' => $type,
            'value' => $value
        ];

    }, $allKeys);
    printDifferecne($resultTree);
//    var_dump($resultTree);
}

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

genDiff($tree1, $tree2);
