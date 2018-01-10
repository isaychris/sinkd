<?php

/*
//===================================================================================================
// File: create.js
// Description: This file is used to create a new folder in the server.
//===================================================================================================
*/

$path = $_POST['userdir'] . "/";
$name = $_POST['name'];
$status = 0; 

$i = 1;
$new_path = $path . $name;

// if the folder already exists in the server, rename it.
if (file_exists($new_path)) {

  // keep renaming the folder with an iterator, until it is unique.
  while (file_exists($new_path)) {
    $new_name = pathinfo($path . $name, PATHINFO_FILENAME);
    $new_path = $path . $new_name . '_' . $i;
    $i++;
  }

  $status = 1; // means the folder was renamed with iterator
}

// create the directory in the server.
mkdir($new_path);

// return the status
echo $status;
?>

