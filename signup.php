<?php

/*
//===================================================================================================
// File: signup.php
// Description: This file is the signup page of our website.
//===================================================================================================
*/

    session_start();

    $requestMethod = strtoupper(getenv('REQUEST_METHOD'));
    if ($requestMethod == 'POST')
    {
        if (isset($_POST['signup'])) {
            require 'db.php';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title> Sinkd - Sign Up </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/signup.css">
</head>

<body class="body">
  <nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><img src="assets/logo.png" width="30" height="30" class="d-inline-block align-top" alt=""> Sinkd </a>
  </nav>
  <div class="container">
    <div class="card" id="wrapper">
      <div class="card-body">
        <h1 class="text-center" id="header">Drop anchor with us!</h1>
        <form action="signup.php" method="post" autocomplete="off">
          <input type="text" class="form-control" placeholder="Name" name="displayname" required>
          <br>
          <input type="text" class="form-control" placeholder="Username" name="username" required>
          <br>
          <input type="password" class="form-control" placeholder="Password" name="password" required>
          <br>
          <input type="password" class="form-control" placeholder="Confirm Password" name="confirmpassword" required>
          <br>
          <button type="submit" name="signup" class="btn btn-success" id="loginButton">Create</button>
          <input type="hidden" name="page" value="signup">
        </form>
        <p class="text-center" id="join">Already a member? <a href="login.php">Sign in</a>
        </p>
      </div>
    </div>
  </div>

</body>

</html>
