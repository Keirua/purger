#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application as ConsoleApplication;
use \Purger\Command\TestCommand;

$app = new ConsoleApplication('Purger', '0.1.0');
$app->addCommands(array(
    new TestCommand(),
));

$app->run();