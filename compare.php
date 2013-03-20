<?php
use Robocop\RobocopApplication;

require 'vendor/autoload.php';

$app = new RobocopApplication();

try {
  $app->run('compareDir');
} catch (Exception $e) {
  echo $e->getMessage() . "\n";
}

/*
require 'vendor/autoload.php';

$dir = '/home/peter/cctv';

$dir = $dir . '/2013-03-19';

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
    $val = Robocop\Image::compare($images[$prev], $images[$key]);
    echo str_replace($dir, '', $images[$key]) . ': ' . $val . "\n";
  }
}

// 5000 seems to be a good value to be a real change
*/
