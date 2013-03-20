<?php
/**
 *
 * @see http://www.imagemagick.org/script/index.php
 * @author theapi
 *
 */

namespace Robocop;

/**
 * Send email
 */
class Image
{

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
