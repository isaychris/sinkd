<?php

/*
//===================================================================================================
// File: populate.php
// Description: This file is used to retrieve the files and folders from the server.
//===================================================================================================
*/

function getWebPath($path)
{
  $arr = explode("/", $path);
  $path = implode("/", array_slice($arr, 4));
  $webhost = "http://".$_SERVER['HTTP_HOST'] . "/";
  return $webhost . $path;
}

function convertSuffix($size) {
  $base = log($size) / log(1024);
  $suffix = array(
    "B",
    "KB",
    "MB",
    "GB",
    "TB"
  );
  $f_base = floor($base);
  return round(pow(1024, $base - floor($base)) , 1) . ' ' . $suffix[$f_base];
}

date_default_timezone_set('america/los_angeles');
$dir = $_POST['userdir'] . "/";
$result = array();

// display a list of files available
if ($files = scandir($dir)) {
  foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
      //$filename = preg_replace('/\s/i', '%20', $file);
      //$modtime = date('m/d/y - h:i A', filemtime($dir . $filename));
      $modtime = date('m/d/y - h:i A', filemtime($dir . $file));
      $size = filesize($dir . $file);
      array_push($result, array(
        'name' => basename($file) ,
        'size' => convertSuffix($size) ,
        'mod' => $modtime,
        'path' => $dir . $file,
        'type' => is_file($dir . $file) ? 'true' : 'false',
        'web' => getWebPath($dir) . $file,
      ));
    }
  }
}

echo json_encode($result);
?>
