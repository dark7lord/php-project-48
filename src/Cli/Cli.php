<?php

namespace Differ\Cli;

use Docopt;

use function Differ\Differ\genDiff;
use function Differ\Parsers\parseFile;

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

//    TODO: не работает
    if ($arguments['--version']) {
        echo '1.0';
        exit;
    }

    $filename1 = $arguments['<filename1>'];
    $filename2 = $arguments['<filename2>'];

    $result = genDiff($filename1, $filename2);
    print_r($result);
}
