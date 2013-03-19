<?php
/**
 *
 * @see http://phpmailer.worxware.com
 * @author theapi
 *
 */

namespace Robocop;

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
   * Constructor
   *
   */
  public function __construct() {
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
    $this->mailer->SetFrom($this->conf['email']['from']);
    $this->mailer->AddAddress($this->conf['email']['to']);
    $this->prepareGmail();
  }

  /**
   * Get all the variable set for sending via Gmail
   */
  public function prepareGmail() {
    $this->mailer->IsSMTP(); // telling the class to use SMTP
    $this->mailer->SMTPAuth   = true;                  // enable SMTP authentication
    $this->mailer->SMTPSecure = "tls";                 // sets the prefix to the servier
    $this->mailer->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $this->mailer->Port       = 587;                   // set the SMTP port for the GMAIL server
    $this->mailer->Username   = $this->conf['smtp']['username']; // GMAIL username
    $this->mailer->Password   = $this->conf['smtp']['password']; // GMAIL password
  }

}
