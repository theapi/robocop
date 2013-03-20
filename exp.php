<?php
use Robocop\RobocopApplication;

require 'vendor/autoload.php';

$app = new RobocopApplication();

try {
  $app->run();
} catch (Exception $e) {
  echo $e->getMessage() . "\n";
}



/*
try {
  $config = new Config();
  var_dump($config);
  var_dump($config->getSaveDir());
} catch (Exception $e) {
  echo $e->getMessage() . "\n";
}


$mailer = new PHPMailer();

$parser = new MimeMailParser();

$m = new Mailer($config);

$mp = new MailParser($config);
var_dump($mp);
*/

