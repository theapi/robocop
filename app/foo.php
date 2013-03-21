#!/usr/bin/env php
<?php
require 'vendor/autoload.php'; // fix this to know where it is

use Theapi\Robocop\Console\FooApplication;

$application = new FooApplication();
$application->run();