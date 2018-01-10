<?php

/*
//===================================================================================================
// File: delete_account.php
// Description: This file is used to delete the account off the server and database.
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

$user_path = $_POST['user_path'];
removeDirectory($user_path);

echo "Account deleted from server";

// =====================================================================================================================

$link = mysqli_connect('localhost', 'group_a', 'akN6VbQ1QBVXknne', 'group_a');

if(mysqli_connect_errno()){
	echo "Connection failed: " . mysqli_connect_error();
	exit();
}

$username = $_POST['user'];

// remove the the users data from the database
$updateQuery = "DELETE FROM login WHERE username = '$username'";
$link->query($updateQuery);

echo "Account deleted from database";
?>
