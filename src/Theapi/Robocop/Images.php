<?php
namespace Theapi\Robocop;

use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @see http://www.imagemagick.org/script/index.php
 * @author theapi
 *
 */

/**
 * Do stuff with images
 */
class Images
{

  /**
   * The configuration array
   */
  protected $config;

  /**
   * Constructor
   */
  public function __construct($config, $output) {
    $this->config = $config;
    $this->output = $output;
  }

  public function compareDir($dir) {
    if (!is_dir($dir)) {
      throw new \Exception($dir . ' is not a directory');
    }
    $images = array();
    $files = scandir($dir);
    foreach ($files as $file) {
      if (substr($file, -4) == '.jpg') {
        $images[] = $dir . '/' . $file;
      }
    }

    foreach ($images as $key => $image) {
      if ($key > 0) {
        $prev = $key - 1;
        $val = $this->compare($images[$prev], $images[$key]);

        //TODO: make the noticeable diff a config item
        $img = trim(str_replace($dir, '', $images[$key]), '/');
        if ($val > 5000) {
          $text = '<question>' . $img . '</question>';
          $text .= ': <info>' . $val . '</info>';
          $this->output->writeln($text);
        }
        else {
          //$text = $img;
        }
        //$text .= ': <info>' . $val . '</info>';

        //$this->output->writeln($text);
      }
    }
  }

  /**
   * Compute how different two images are.
   *
   * @see http://www.imagemagick.org/Usage/compare/
   */
  public static function compare($img_a, $img_b, $fuzz = 10) {
    $cmd = 'compare -metric AE -fuzz ' . escapeshellarg($fuzz) . '% ' . escapeshellarg($img_a) . '  ' . escapeshellarg($img_b) . ' null: 2>&1';
    $output = shell_exec($cmd);
    return trim($output);
  }


}
