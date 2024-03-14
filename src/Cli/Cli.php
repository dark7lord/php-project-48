<?php

namespace Differ\Cli;

use Docopt;
use RuntimeException;

use function Differ\Differ\genDiff;

function readJsonFile($filename)
{
    if (file_exists($filename)) {
        $fileContent = file_get_contents($filename);
        $tree = json_decode($fileContent, true);
        if ($tree === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Error decoding JSON in file $filename");
        }
        return $tree;
    } else {
        throw new RuntimeException("Error reading file $filename");
    }
}

function startCli(): void
{
    $doc = <<<DOC
Generate diff.

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <filename1> <filename2>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
DOC;

    $params = [
        'argv' => $_SERVER['argv']
    ];

    $arguments = Docopt::handle($doc, $params);

    if ($arguments['--help']) {
        echo $doc;
        exit;
    }

    if ($arguments['--version']) {
        echo '1.0';
        exit;
    }

    $filename1 = $arguments['<filename1>'];
    $filename2 = $arguments['<filename2>'];

    try {
        $tree1 = readJsonFile($filename1);
        $tree2 = readJsonFile($filename2);

        $result = genDiff($tree1, $tree2);
        var_dump($result);
    } catch (RuntimeException $e) {
        echo $e->getMessage();
        exit;
    }
}
