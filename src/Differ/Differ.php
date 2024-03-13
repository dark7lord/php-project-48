<?php

namespace Differ\Differ;

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
    $value = function ($type, $data) {
        return ['type' => $type, 'value' => $data];
    };

    switch (true) {
        case in_array($key, $deletedKeys):
            return $value('deleted', $tree1[$key]);
        case in_array($key, $addedKeys):
            return $value('added', $tree2[$key]);
        default:
            $oldValue = $tree1[$key];
            $newValue = $tree2[$key];
            return ($oldValue === $newValue) ?
                $value('unchanged', $oldValue) :
                $value('changed', ['oldValue' => $oldValue, 'newValue' => $newValue]);
    }
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

function genDiff($tree1, $tree2): string
{
    $treeDiff = generateDiff($tree1, $tree2);
    $result = makeDiffToString($treeDiff);

    return  $result;
}
