<?php

/*
//===================================================================================================
// File: upload.php
// Description: This file is used to upload a selected file to the server.
//===================================================================================================
*/

$path = $_POST['dir'] . "/";
$filename = $_FILES["file"]["name"];
$status = 0;

if ($filename != '')
  {
  $i = 1;
  $targetpath = $path . $filename;
  $name = pathinfo($path . $filename, PATHINFO_FILENAME);
  $ext = pathinfo($$path . $filename, PATHINFO_EXTENSION);
  if (file_exists($targetpath))
    {
    while (file_exists($targetpath))
      {
      $targetpath = $path . $name . '_' . $i . '.' . $ext;
      $i++;
      }
    $status = 1;
    }
  move_uploaded_file($_FILES["file"]["tmp_name"], $targetpath);
  echo $status;
}
?>