<?php
use Robocop\RobocopApplication;

require 'vendor/autoload.php';

$app = new RobocopApplication();

try {
  $app->run('sendTestMail');
} catch (Exception $e) {
  echo $e->getMessage() . "\n";
}
