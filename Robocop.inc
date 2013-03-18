<?php

require_once 'php-mime-mail-parser/MimeMailParser.class.php';

class Robocop 
{
  
  protected $conf;
  protected $from;
  protected $to;
  protected $gmail_username;
  protected $gmail_password;
  
  /**
   * Where the attachments are saved
   */
  protected $save_dir = '/home/peter/cctv';
  
  /**
   * The MimeMailParser object
   */
  protected $parser;
  
  /**
   * The PHPMailer object
   */
  protected $mailer;
 
  /**
   * Constructor
   * 
   */
  public function __construct($conf) {
    $this->conf = $conf;
    $this->save_dir = $this->conf['save_dir'];

    /*
    $this->from = $from;
    $this->to = $to;
    $this->gmail_username = $gmail_username;
    $this->gmail_password = $gmail_password;
    */
    
    $this->parser = new MimeMailParser();
  }
  
  public function processIncomingMail() {
    $this->parser->setStream(STDIN);
    
    //$this->processSnaps();
    
    
    $subject = $this->parser->getHeader('subject');
    switch ($subject) {
      case 'from your dvr\'s snap jpg':
        $this->processSnaps();
        break;
      default:
        $this->passOnMessage();
        break;
    }
    
    
  }
  
  /**
   * Get the PHPMailer object
   */
  public function getMailer() {
    if (!is_object($this->mailer)) {
      include_once 'PHPMailer/class.phpmailer.php';
      $this->mailer = new PHPMailer();
    }
    return $this->mailer;
  }
  
  /**
   * Compute how different two images are.
   *
   * @see http://www.imagemagick.org/Usage/compare/
   */
  public static function compareImages($img_a, $img_b, $fuzz = 10) {
    $cmd = 'compare -metric AE -fuzz ' . escapeshellarg($fuzz) . '% ' . escapeshellarg($img_a) . '  ' . $img_b . ' null: 2>&1';
    $output = shell_exec($cmd);
    return trim($output);
  }
  
  public function prepareEmail() {
    $mail = $this->getMailer();
    
    $mail->SetFrom($this->conf['email']['from']);
    $mail->AddAddress($this->conf['email']['to']);
    $this->prepareGmail();
    
    return $mail;
  }
  
  /**
   * Get all the variable set for sending via Gmail
   */
  public function prepareGmail() {
    $mail = $this->getMailer();
    
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
    $mail->Username   = $this->conf['smtp']['username']; // GMAIL username
    $mail->Password   = $this->conf['smtp']['password']; // GMAIL password
 
  }
  
  /**
   * Sends the alarm message that was received
   */
  protected function passOnMessage() {
    $mail = $this->prepareEmail();
    $mail->Subject = $this->parser->getHeader('subject');
    $mail->Body = $this->parser->getMessageBody('text');
    
    $html = $this->parser->getMessageBody('html');
    if (!empty($html)) {
      $mail->MsgHTML($html);
    }
   
    $mail->Send();
    
  }
  
  protected function processSnaps() {
    if (!$this->saveAttachments()) {
      // no attachments
      return;
    }
    
    // When was the last email sent?
    
    // If not too recent, attach the images that have not yet been sent
    
  }
  
  protected function saveAttachments() {

    $attachments = $this->parser->getAttachments();
    if (is_array($attachments) && count($attachments) > 0 ) {
      $dir = $this->save_dir . '/' . date('Y-m-d');
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
      return $attachments;
    }
    return false;
  }
  
}
