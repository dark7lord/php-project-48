<?php

namespace Gendiff\Cli;

use Docopt;

function startCli()
{
    $doc = <<<DOC
Generate diff.

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)

Options:
  -h --help                     Show this screen
  -v --version                  Show version
DOC;

    $arguments = Docopt::handle($doc);

    if ($arguments['--help']) {
        echo $doc;
        exit;
    }

    if ($arguments['--version']) {
        echo '1.0';
        exit;
    }
}
