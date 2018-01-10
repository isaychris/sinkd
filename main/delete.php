<?php

/*
//===================================================================================================
// File: delete.php
// Description: This file is used to remove the selected file or folder from the server.
//===================================================================================================
*/

// PURPOSE: Recursively removes a folder along with its files.
function removeDirectory($path) {
  $files = glob($path . '/*');
  foreach ($files as $file) {
    is_dir($file) ? removeDirectory($file) : unlink($file);
  }
  rmdir($path);
  return;
}

$filedata = $_POST['file'];

// it is a file
if (!is_dir($filedata)) {
  unlink($filedata);
}

// it is a folder
else {
  removeDirectory($filedata);
}
?>
