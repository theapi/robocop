<?php
namespace Robocop;

/**
 *
 * @author theapi
 *
 */

/**
 * Configuration for Robocop
 */
class Config
{

  protected $conf;

  /**
   * Constructor
   *
   */
  public function __construct() {
    $srcDir = dirname(__DIR__);
    $baseDir = dirname($srcDir);

    if (!file_exists($baseDir . '/config.php')) {
      throw new \Exception($baseDir . '/config.php is missing');
    }
    include $baseDir . '/config.php';
    $this->conf = $conf;
  }

  public function getSaveDir() {
    if (!isset($this->conf['save_dir'])) {
      $this->conf['save_dir'] = '/tmp';
    }
    return $this->conf['save_dir'];
  }

  public function getEmail() {
    if (isset($this->conf['email'])) {
      $this->conf['email'];
    }
    return NULL;
  }

  public function getSmtp() {
    if (isset($this->conf['smtp'])) {
      $this->conf['smtp'];
    }
    return NULL;
  }

}
