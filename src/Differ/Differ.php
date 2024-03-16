<?php

namespace Differ\Differ;

use function Differ\Parsers\parseFile;

function makeDiffToString($tree, $offset = 2): string
{
    $padding = str_repeat(' ', $offset);
    $lines = array_map(function ($property) use ($padding) {
        [
            'key' => $key,
            'type' => $type,
            'value' => $value
        ] = $property;

        $formattedValue = is_bool($value) ? ($value ? 'true' : 'false') : $value;

        return match ($type) {
            'unchanged' => "$padding   $key: $formattedValue",
            'added' => "$padding + $key: $formattedValue",
            'deleted' => "$padding - $key: $formattedValue",
            'changed' => "$padding - $key: {$value['oldValue']}\n$padding + $key: {$value['newValue']}",
            default => ''
        };
    }, $tree);

    return implode("\n", ['{', ...$lines, "}"]);
}

function createDiffItem($key, $tree1, $tree2, $deletedKeys, $addedKeys): array
{
    $value = fn ($type, $data) => ['type' => $type, 'value' => $data];

    return match (true) {
        in_array($key, $deletedKeys) => $value('deleted', $tree1[$key]),
        in_array($key, $addedKeys) => $value('added', $tree2[$key]),
        default => (function () use ($tree1, $tree2, $key, $value) {
            $oldValue = $tree1[$key];
            $newValue = $tree2[$key];
            return ($oldValue === $newValue) ?
                $value('unchanged', $oldValue) :
                $value('changed', ['oldValue' => $oldValue, 'newValue' => $newValue]);
        })()
    };
}


function generateDiff($tree1, $tree2): array
{
    $keys1 = array_keys($tree1);
    $keys2 = array_keys($tree2);

    $deletedKeys = array_diff($keys1, $keys2);
    $addedKeys = array_diff($keys2, $keys1);
    $allKeys = array_unique(array_merge($keys1, $keys2));
    sort($allKeys);

    return array_map(
        fn($key) => ['key' => $key] + createDiffItem($key, $tree1, $tree2, $deletedKeys, $addedKeys),
        $allKeys
    );
}

function genDiff($filename1, $filename2): string
{
    $tree1 = parseFile($filename1);
    $tree2 = parseFile($filename2);
    $treeDiff = generateDiff($tree1, $tree2);
    $result = makeDiffToString($treeDiff);

    return  $result;
}
