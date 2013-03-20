<?php
namespace Robocop;

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
   * The configuration object
   */
  protected $config;

  /**
   * Constructor
   */
  public function __construct($config) {
    $this->config = $config;
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
        echo str_replace($dir, '', $images[$key]) . ': ' . $val . "\n";
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
