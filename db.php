<?php

/*
//===================================================================================================
// File: db.php
// Description: This file is used to interact with the database for account creation and login.
//===================================================================================================
*/

session_start();
$page = $_POST['page'];
$link = new mysqli('localhost', 'group_a', 'akN6VbQ1QBVXknne', 'group_a');

//check connection
if ($link->connect_error) {
	die("Connection failed: " . $link->connect_error);
}
////////////////////////////FIRST are we logging in
if ($page == "login") {
	// check to see if user exists first
	$username = $link->escape_string($_POST['username']);
	$qresult = $link->query("SELECT * FROM login WHERE username='$username'");

	//no take result and fetch pass out of it
	$user_row = $qresult->fetch_assoc();

	$pass = $_POST['password'];
	$dbpass = $user_row['password'];

	if ($qresult->num_rows == 0) { //user not in database!
		$message = "You are not in the database!";
		echo "<script type='text/javascript'>alert('$message');</script>";

	} elseif ($pass == $dbpass){
		$message = "Correct Password!";
		echo "<script type='text/javascript'>alert('$message');</script>";
		$_SESSION['login'] = true;
		$_SESSION['username'] = $user_row['username'];
       		$_SESSION['user_path'] = $user_row['filepath'];
		$_SESSION['displayname'] = $user_row['displayname'];
		header("location: main.php");
	}

	else {
		$message = "Wrong password!";
		echo "<script type='text/javascript'>alert('$message');</script>";
	}
	$link->close();

/////////////////////////////////////////////////OR signing up
} else if ($page == "signup"){

	$pass = $_POST['password'];
	$cpass = $_POST['confirmpassword'];

	//check to see if user exists
	$qresult = $link->query("SELECT * FROM login WHERE username='$username'");



	if ($pass == $cpass){

		$displayname = $link->escape_string($_POST['displayname']);
		$username = $link->escape_string($_POST['username']);
		$password = $link->escape_string($_POST['password']);
		$user_path = $username . "/";

			//need to create user
		$insertsql = "INSERT INTO login (displayname, username, password, filepath) VALUES ('$displayname','$username','$password','$user_path')";

		if ($link->query($insertsql)) {
			$message = "SUCESSFUL INSERT";
			echo "<script type='text/javascript'>alert('$message');</script>";

			$root_dir = $_SERVER['DOCUMENT_ROOT']."/group_A/sinkd/db/".$user_path;

			if(!file_exists($root)) {
				$message = "Directory = $root_dir";
				echo "<script type='text/javascript'>alert('$message');</script>";

				mkdir($root_dir, 0777);
				$_SESSION['login'] = true;
				$_SESSION['username'] = $username;
     				$_SESSION['user_path'] = $user_path;
				$_SESSION['displayname'] = $displayname;

				header("location: main.php");
			} else {
				$message = "Directory already present";
				echo "<script type='text/javascript'>alert('$message');</script>";
				die();
			}
		} else {
			$message = "Already in database";
			echo "<script type='text/javascript'>alert('$message');</script>";
		}

	} else { 
		$message = "Passwords don't match";
		echo "<script type='text/javascript'>alert('$message');</script>";
	}
}
?>
