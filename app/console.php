#!/usr/bin/env php
# app/console.php
<?php
require 'vendor/autoload.php'; // fix this to know where it is

use Theapi\Robocop\Console\Command\GreetCommand;
use Symfony\Component\Console\Application;

$application = new Application('Robocop tutorial console', '0.5');
$application->add(new GreetCommand);
$application->run();