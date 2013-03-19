<?php
require 'vendor/autoload.php';

$mailer = new PHPMailer();

$parser = new MimeMailParser();

$m = new Robocop\Mailer();

$mp = new Robocop\MailParser();
var_dump($mp);

