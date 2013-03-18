<?php


require_once 'Robocop.inc';


$robocop = new Robocop($from, $to, $gmail_username, $gmail_password);
$robocop->processIncomingMail();


/*
$save_dir = '/home/peter/cctv';

//$contents = stream_get_contents(STDIN);
//error_log(print_r($contents, 1), 3, "/tmp/robocop.log");

require_once 'php-mime-mail-parser/MimeMailParser.class.php';

$Parser = new MimeMailParser();
$Parser->setStream(STDIN);

$attachments = $Parser->getAttachments();
if (is_array($attachments) && count($attachments) > 0 ) {
  $dir = $save_dir . '/' . date('Y-m-d');
  foreach($attachments as $attachment) {
    // get the attachment name
    $filename = $attachment->filename;
    // write the file to the directory you want to save it in
    @mkdir($dir, 0755, true);
    if ($fp = fopen($dir . '/' . $filename, 'w')) {
      while($bytes = $attachment->read()) {
        fwrite($fp, $bytes);
      }
      fclose($fp);
    }
  }
  
}
*/

