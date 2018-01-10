<?php

/*
//===================================================================================================
// File: rename.php
// Description: This file is used to rename a selected file or folder in the server.
//===================================================================================================
*/

$path    = $_POST['dir'] . "/";
$oldname = $_POST['old'];
$newname = $_POST['new'];
$status  = 0;

$i = 1;
$new_path = $path . $newname;

if (file_exists($new_path)) {
  $name = pathinfo($path . $newname, PATHINFO_FILENAME);
  $ext  = pathinfo($path . $newname, PATHINFO_EXTENSION);

  while (file_exists($new_path)) {
    $new_path = ($ext == '') ? $path . $name . '_' . $i : $path . $name . '_' . $i . '.' . $ext;
    $i++;
  }
  
  $status = 1;
}

rename($path . $oldname, $new_path);
echo $status;
?>