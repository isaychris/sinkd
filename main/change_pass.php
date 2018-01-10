<?php

/*
//===================================================================================================
// File: change_pass.php
// Description: This file is used to change the password of the user.
//===================================================================================================
*/
//open connection to database
$link = mysqli_connect('localhost', 'group_a', 'akN6VbQ1QBVXknne', 'group_a');
//Exit if connection failed
if(mysqli_connect_errno()){
	echo "Connection failed: " . mysqli_connect_error();
	exit();
}
//Password and username from javascript call 
$newPass = $_POST['pass'];
$userName = $_POST['user'];
//Repeat username 
echo "username is ".$userName;
//Update Query database for username and password
$updateQuery = "UPDATE login SET password = '$newPass' WHERE username = '$userName'";
$result= $link->query($updateQuery);
//If no rows affected output error
if(mysqli_num_rows($result)==0)
{
	die("<div><p>No results found</p></div>");
}

?>
