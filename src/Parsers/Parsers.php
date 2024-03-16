<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

function parseJson(string $data): array
{
    $parsedData = json_decode($data, true);
    if ($parsedData === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new \InvalidArgumentException("Error decoding JSON data");
    }
    return $parsedData;
}

function parseYaml(string $data): array
{
    try {
        return Yaml::parse($data);
    } catch (ParseException $e) {
        throw new \InvalidArgumentException("Error parsing YAML data: " . $e->getMessage());
    }
}

function createParser(string $format): callable
{
    return match ($format) {
        'json' => fn(string $data): array => parseJson($data),
        'yaml', 'yml' => fn(string $data): array => parseYaml($data),
        default => throw new \InvalidArgumentException("Unsupported format: $format")
    };
}

function parseFile(string $filename): array
{
    try {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $fnParseFile = createParser($extension);
        $fileContent = file_get_contents($filename);
        return $fnParseFile($fileContent);
    } catch (\InvalidArgumentException $e) {
        echo $e->getMessage();
        exit;
    }
}
