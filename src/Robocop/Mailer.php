<?php
namespace Robocop;

/**
 *
 * @see http://phpmailer.worxware.com
 * @author theapi
 *
 */


/**
 * Send email
 */
class Mailer
{

  /**
   * The PHPMailer object
   */
  protected $mailer;

  /**
   * The configuration object
   */
  protected $config;

  /**
   * Constructor
   *
   */
  public function __construct($config) {
    $this->config = $config;
    $this->mailer = new \PHPMailer();
  }

  /**
   * Get the PHPMailer object
   */
  public function getMailer() {
    return $this->mailer;
  }

  public function send() {
    $this->mailer->Send();
  }

  public function prepareEmail() {
    $email = $this->config->getEmail();
    $this->mailer->SetFrom($email['from']);
    $this->mailer->AddAddress($email['to']);
    $this->prepareGmail();
  }

  /**
   * Get all the variable set for sending via Gmail
   */
  public function prepareGmail() {
    $smtp = $this->config->getSmtp();
    $this->mailer->IsSMTP(); // telling the class to use SMTP
    $this->mailer->SMTPAuth   = true;                  // enable SMTP authentication
    $this->mailer->SMTPSecure = "tls";                 // sets the prefix to the servier
    $this->mailer->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $this->mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
    $this->mailer->Username   = $smtp['username']; // GMAIL username
    $this->mailer->Password   = $smtp['password']; // GMAIL password
  }

}
