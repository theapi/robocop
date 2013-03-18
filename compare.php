<?php

require_once 'Robocop.inc';

//echo Robocop::compareImages('/home/peter/cctv/2013-03-16/CH01_13_03_16_23_56_23.jpg', '/home/peter/cctv/2013-03-16/CH01_13_03_16_23_56_20.jpg');

$dir = '/home/peter/cctv';

$dir = $dir . '/2013-03-18';

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
    $val = Robocop::compareImages($images[$prev], $images[$key]);
    echo str_replace($dir, '', $images[$key]) . ': ' . $val . "\n";
  }
}

// 5000 seems to be a good value to be a real change
