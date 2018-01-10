<?php

/*
//===================================================================================================
// File: login.php
// Description: This file is the login page of our website.
//===================================================================================================
*/

    session_start();

    if(isset($_SESSION['login'])) {
  	header('Location: main.php');
    } else {
   	 $requestMethod = strtoupper(getenv('REQUEST_METHOD'));
    	if ($requestMethod == 'POST')
    	{
        	if (isset($_POST['login'])) {
            	require 'db.php';
        	}
    	}
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title> Sinkd - Login </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/login.css">
</head>

<body class="body">
  <nav class="navbar navbar-dark bg-dark">
    <a id="navbarHeader" class="navbar-brand" href="#"><img src="assets/logo.png" width="30" height="30" class="d-inline-block align-top" alt=""> Sinkd </a>
  </nav>
  <div class="container">
    <div class="card" id="wrapper">
      <div class="card-body">
        <h1 class="text-center" id="header">Bring everything, and the kitchen sink</h1>
        <form action="login.php" method="post" autocomplete="off">
          <input type="text" class="form-control form-control-md" placeholder="Username" name="username">
          <br>
          <input type="password" class="form-control" placeholder="Password" name="password">
          <br>
          <button type="submit"  name="login" class="btn btn-primary" id="loginButton">Anchor In</button>
          <input type="hidden" name="page" value="login">
        </form>
        <p class="text-center" id="join">Not a member? <a href="signup.php">Join now!</a>
        </p>
      </div>
    </div>
  </div>
</body>

</html>
