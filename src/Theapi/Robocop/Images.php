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

  public function compareDir($imagesDir = null, $diffThreshold = null) {

    if (empty($imagesDir)) {
      // Use the directory for today
      $imagesDir = date('Y-m-d');
    }

    $dir = $this->config['save_dir'] . '/' . trim($imagesDir, '/');
    if (!is_dir($dir)) {
      throw new \Exception($dir . ' is not a directory');
    }
    $destinationDir = $this->config['save_dir'] . '/detected_' . trim($imagesDir, '/');

    if (empty($diffThreshold)) {
      $diffThreshold = $this->config['images']['diff_threshold'];
    }
    settype($diffThreshold, 'int');

    $images = array();
    $files = scandir($dir);
    foreach ($files as $file) {
      if (substr($file, -4) == '.jpg') {
        $images[] = $dir . '/' . $file;
      }
    }

    $i = 0;
    foreach ($images as $key => $image) {
      if ($key > 0) {
        $prev = $key - 1;
        $val = $this->compare($images[$prev], $images[$key]);

        $img = trim(str_replace($dir, '', $images[$key]), '/');
        if ($val > $diffThreshold) {
          $text = $img . ': <info>' . $val . '</info>';
          $this->output->writeln($text);

          // copy image to a separate directory
          if (!file_exists($destinationDir)) {
            if (!mkdir($destinationDir, 0777, true)) {
              throw new \Exception('Unable to create Path [' . $destinationDir . ']');
            }
          }
          $this->copyWithDatestamp($i, $dir, $destinationDir, $img);
          $i++;
        } else {
          $this->output->writeln($img . ': ' . $val);
        }
      }
    }

    // Create a video of the detected images
    $this->createVideo($destinationDir);
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

  public function createVideo($dir) {
    // avconv -v quiet -r 1 -f image2 -i img_%04d.jpg -r 25 -b 65536k a.avi
    $dir = escapeshellarg($dir);
    $cmd = 'avconv -v quiet -r 1 -f image2 -i ' . $dir . '/img_%04d.jpg -r 25 -b 65536k ' . $dir . '/detected.avi';
    $output = shell_exec($cmd);
    return trim($output);
  }

  protected function copyWithDatestamp($i, $source, $destination, $imgName) {
    $string = $imgName;
    if ($im = imagecreatefromjpeg($source . '/' . $imgName)) {
      $textColor = imagecolorallocate ($im, 0, 0,0);
      imagestring ($im, 5, 3, 3, $string, $textColor);
      imagejpeg($im, $destination . '/img_' . sprintf('%04d', $i) . '.jpg', 100);
    }
  }

}
