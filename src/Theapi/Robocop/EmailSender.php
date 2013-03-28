<?php
namespace Theapi\Robocop;

/**
 *
 * @see http://swiftmailer.org/docs
 * @author theapi
 *
 */


/**
 * Sends email
 */
class EmailSender
{

  /**
   * The configuration array
   */
  protected $config;

  /**
   * Constructor
   *
   */
  public function __construct($config) {
    $this->config = $config;
  }

  public function sendSpool($messageLimit = 10, $timeLimit = 100, $recoverTimeout = 900) {
    $fileSpool = new \Swift_FileSpool($this->config['email']['spool_dir']);
    $spoolTransport = \Swift_SpoolTransport::newInstance($fileSpool);

    // Create the SMTP Transport
    $transport = \Swift_SmtpTransport::newInstance($this->config['email']['host'], $this->config['email']['port'], 'tls')
      ->setUsername($this->config['email']['username'])
      ->setPassword($this->config['email']['password'])
      ;

    $spool = $spoolTransport->getSpool();
    $spool->setMessageLimit($messageLimit);
    $spool->setTimeLimit($timeLimit);
    if (null !== $recoverTimeout) {
        $spool->recover($recoverTimeout);
    } else {
        $spool->recover();
    }
    return $spool->flushQueue($transport);
  }

  public function sendMail($subject, $body, $filePath = NULL, $viaSpool = false) {
    $message = \Swift_Message::newInstance($subject)
      ->setFrom(array($this->config['email']['from']))
      ->setTo(array($this->config['email']['to']))
      ->setBody($body)
      ;
    if (!empty($filePath)) {
      $message->attach(\Swift_Attachment::fromPath($filePath));
    }

    if ($viaSpool) {
      $spool = new \Swift_FileSpool($this->config['email']['spool_dir']);
      $transport = \Swift_SpoolTransport::newInstance($spool);
    } else {
      // Create the SMTP Transport
      $transport = \Swift_SmtpTransport::newInstance($this->config['email']['host'], $this->config['email']['port'], 'tls')
        ->setUsername($this->config['email']['username'])
        ->setPassword($this->config['email']['password'])
        ;
    }

    // Create the Mailer using the Transport
    $mailer = \Swift_Mailer::newInstance($transport);

    return $mailer->send($message);

  }

  public function sendTestMail($viaSpool = false) {

    // Create a message
    if ($viaSpool) {
      $body = 'This message was sent from the spool.';
    } else {
      $body = 'This message was sent directly via SMTP.';
    }

    $subject = 'Test from Robocop via Swift mailer';
    $filePath = __DIR__ . '/peter.jpg';

    return $this->sendMail($subject, $body, $filePath, $viaSpool);
  }

  public function processSpoolInBackground() {
    //TODO: use shell_exec to call /app/robocop email:send in the background
  }
}
