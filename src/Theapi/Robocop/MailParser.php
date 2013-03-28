<?php
namespace Theapi\Robocop;

/**
 *
 * @see http://pecl.php.net/package/mailparse
 * @see http://php-mime-mail-parser.googlecode.com
 * @author theapi
 *
 */

/**
 * Parse email
 */
class MailParser
{

  /**
   * The MimeMailParser object
   */
  protected $parser;

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
    $this->parser = new \MimeMailParser();
  }

  public function processIncomingMail() {
    $this->parser->setStream(STDIN);
    $subject = $this->parser->getHeader('subject');
    switch ($subject) {
      case 'from your dvr\'s snap jpg':
        $this->processSnaps();
        break;
      default:
        $this->passOnMessage();
        //$this->processSnaps();
        break;
    }
  }

  protected function processSnaps() {
    if (!$this->saveAttachments()) {
      // no attachments
      return;
    }

    // When was the last email sent?

    // If not too recent, attach the images that have not yet been sent

  }

  protected function passOnMessage() {
    // send with swiftmail
    // TODO: get the mailer via dependency injection
    $mailer = new EmailSender($this->config);
    $subject = $this->parser->getHeader('subject');
    $body = $this->parser->getMessageBody('text');
    $viaSpool = false; //TODO get via_spool from config
    $sent = $mailer->sendMail($subject, $body, null, $viaSpool);
    if ($viaSpool) {
      $mailer->processSpoolInBackground();
    }
  }

  protected function saveAttachments() {
    $attachments = $this->parser->getAttachments();
    if (is_array($attachments) && count($attachments) > 0 ) {
      $dir = $this->config['save_dir'] . '/in_' . date('Y-m-d');
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
