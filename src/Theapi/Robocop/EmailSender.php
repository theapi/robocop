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
   * A Symfony\Component\Process object
   */
  protected $process;

  /**
   * Constructor
   *
   */
  public function __construct($config) {
    $this->config = $config;
  }

  public function setProcess($process) {
    $this->process = $process;
  }

  public function sendSpool($messageLimit = 10, $timeLimit = 100, $recoverTimeout = 900) {

    if (empty($messageLimit)) {
      $messageLimit = 10;
    }

    if (empty($timeLimit)) {
      $timeLimit = 100;
    }

    if (empty($recoverTimeout)) {
      $recoverTimeout = 900;
    }

    $fileSpool = new \Swift_FileSpool($this->config['spool_dir']);
    $spoolTransport = \Swift_SpoolTransport::newInstance($fileSpool);

    // Create the SMTP Transport
    $transport = \Swift_SmtpTransport::newInstance($this->config['host'], $this->config['port'], 'tls')
      ->setUsername($this->config['username'])
      ->setPassword($this->config['password'])
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

  public function sendMail($subject, $body, $filePath = null, $viaSpool = false) {
    $message = \Swift_Message::newInstance($subject)
      ->setFrom(array($this->config['from']))
      ->setTo(array($this->config['to']))
      ->setBody($body)
      ;
    if (!empty($filePath)) {
      $message->attach(\Swift_Attachment::fromPath($filePath));
    }

    if (is_null($viaSpool) && !empty($this->config['spool'])) {
      $viaSpool = (bool) $this->config['spool'];
    }

    if ($viaSpool) {

      if (!file_exists($this->config['spool_dir'])) {
          if (!mkdir($this->config['spool_dir'], 0755, true)) {
            throw new \Exception('Unable to create spool directory [' . $this->config['spool_dir'] . ']');
          }
      }

      $spool = new \Swift_FileSpool($this->config['spool_dir']);
      $transport = \Swift_SpoolTransport::newInstance($spool);
    } else {
      // Create the SMTP Transport
      $transport = \Swift_SmtpTransport::newInstance($this->config['host'], $this->config['port'], 'tls')
        ->setUsername($this->config['username'])
        ->setPassword($this->config['password'])
        ;
    }

    // Create the Mailer using the Transport
    $mailer = \Swift_Mailer::newInstance($transport);

    $sent = $mailer->send($message);

    if ($viaSpool) {
      $this->processSpoolInBackground();
    }

    return $sent;
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

  public function processSpoolInBackground() { var_dump(ROBOCOP_BIN_PATH . ' email:send -q');
    //TODO: use shell_exec to call /app/robocop email:send in the background
    // NOPE this does not work :(
    $this->process->setCommandLine(ROBOCOP_BIN_PATH . ' email:send -q');
    $this->process->start();
  }
}
