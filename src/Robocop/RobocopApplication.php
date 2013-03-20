<?php
namespace Robocop;

use Robocop\Config,
    Robocop\Images,
    Robocop\Mailer,
    Robocop\MailParser;

/**
 * The container for a collection of commands.
 *
 * A VERY simplified version of Symfony\Component\Console\Application
 *
 */
class RobocopApplication
{
  private $name;
  private $version;

  /**
   * Constructor.
   *
   * @param string $name    The name of the application
   * @param string $version The version of the application
   *
   * @api
   */
  public function __construct($name = 'Robocop', $version = '1.0') {
    $this->name = $name;
    $this->version = $version;
  }

  public function run($input = null, $output = null) {
    $this->doRun($input, $output);
  }

  /**
   * Runs the requested command.
   *
   */
  public function doRun($input, $output) {

    try {
      $config = new Config();
    } catch (Exception $e) {
      throw $e;
    }

    // Parse incoming mail by default
    switch($input) {
      case 'sendQueue':
        $mailer = new Mailer($config);
        $mailer->sendQueue();
        break;
      case 'sendTestMail':
        $mailer = new Mailer($config);
        $mailer->sendTestMail();
        break;
      case 'compareDir':
        if (isset($_SERVER['argv'][1])) {
          $images = new Images($config);
          $images->compareDir($_SERVER['argv'][1]);
        }
        break;
      default:
        $mail_parser = new MailParser($config);
        $mail_parser->processIncomingMail();
        break;
    }

  }

}
