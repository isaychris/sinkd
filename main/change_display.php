<?php

/*
//===================================================================================================
// File: change_display.php
// Description: This file is used to change the display name of the user
//===================================================================================================
*/
//open database connection
$link = mysqli_connect('localhost', 'group_a', 'akN6VbQ1QBVXknne', 'group_a');
//If connection fails to open, exit
if(mysqli_connect_errno()){
	echo "Connection failed: " . mysqli_connect_error();
	exit();
}
//Get old username and new username from main
$oldName = $_POST['oldname'];
$newName = $_POST['newname'];
//Update query to change display name
$updateQuery = "UPDATE login SET displayname = '$newName' WHERE displayname = '$oldName'";
$result= $link->query($updateQuery);
//Update session display name 
session_start();
$_SESSION['displayname'] = $newName;

?>
