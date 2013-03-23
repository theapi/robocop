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

  public function sendTestMail() {

    // Create a message
    $message = \Swift_Message::newInstance('Test from Robocop via Swift mailer')
      ->setFrom(array($this->config['email']['from']))
      ->setTo(array($this->config['email']['to']))
      ->setBody('This is the test message.')
      ->attach(\Swift_Attachment::fromPath(__DIR__ . '/peter.jpg'))
      ;

    // Create the Transport
    $transport = \Swift_SmtpTransport::newInstance($this->config['email']['host'], $this->config['email']['port'], 'tls')
      ->setUsername($this->config['email']['username'])
      ->setPassword($this->config['email']['password'])
      ;

    // Create the Mailer using the Transport
    $mailer = \Swift_Mailer::newInstance($transport);

    return $mailer->send($message);
  }

}
