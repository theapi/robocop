<?php

require_once 'config.php';
require_once 'Robocop.inc';

$robocop = new Robocop($conf);
$mail = $robocop->prepareEmail();


$mail->Subject = 'Test from PHP';
$mail->Body = 'Sending from PHP Mailer';

$mail->SMTPDebug = 1;

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message has been sent";
}
    
