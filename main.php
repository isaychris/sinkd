<?php

/*
//===================================================================================================
// File: main.php
// Description: This file is the main page of our website. It displays the users files/folders from the server.
//===================================================================================================
*/
  session_start();

  // if user is not logged in, redirect user to the login page.
  if(!isset($_SESSION['login'])) {
    header('Location: login.php');
    die();
  } 

  // if user is logged in, retrieve their user data.
  else {
    $root = $_SERVER['DOCUMENT_ROOT']."/group_A/sinkd/db/";
    $user = $_SESSION['username'];
    $display = $_SESSION['displayname'];
    $user_dir = $root.$user;
 }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title> Sinkd - Home </title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" href="assets/logo_dark.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.29.0/css/theme.bootstrap_4.min.css">
  <link rel="stylesheet" href="main/main.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tablesorter@2.29.0/dist/js/jquery.tablesorter.combined.min.js"></script>
  <script> 
	var current_dir = <?php echo json_encode($user_dir); ?> ; 
	var current_user = <?php echo json_encode($user); ?> ; 
	var current_display = <?php echo json_encode($display); ?> ; 
 </script>
  <script type="text/javascript" src="main/main.js"></script>
</head>
<body class="body">
  <!-- navigation bar -->
  <nav class="navbar fixed-top navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><img src="assets/logo.png" width="30" height="30" class="d-inline-block align-top" alt=""> Sinkd </a>
    <div id="btn-group">
      <span title="upload" class="btn btn-success btn-file"><i class="fa fa-cloud-upload"></i> Upload <input type="file" name="file" id="file" class="upload"></span>
      <button title="new folder" type="button" class="btn btn-primary create"><i class="fa fa-plus"></i> Folder </button>
      <button title="settings" type="button" class="btn btn-info account" data-toggle="modal"
      data-target="#myModal"><i class="fa fa-gear"></i>
      </button>
    </div>
  </nav>

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> Account Settings </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
    	<div id="btn-group">
          <button title="change name" type="button" class="btn btn-success EditName"> Edit Name </button>
          <button title="change password" type="button" class="btn btn-success EditPass"> Edit Password </button>
          <button title="delete account" type="button" class="btn btn-danger wipe"> Delete Account </button>
          <button title="logout" type="button" class="btn btn-primary logout"><i class="fa fa-sign-out"></i> Logout </button>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- table container -->
  <div class="container">
    <div class="card" id="wrapper">
      <h4 class="card-header text-center"><span id="display"></span> 's Files </h4>
      <div class="card-body">
        <div id='alert' class='hide'></div>
        <nav class="breadcrumb"></nav>
        <table id="myTable" class="tablesorter">
          <thead>
            <tr>
              <th scope="col"><i class="fa fa-info-circle"></i> Name </th>
              <th scope="col"><i class="fa fa-hdd-o"></i> Size </th>
              <th scope="col"><i class="fa fa-clock-o"></i> Modified </th>
              <th scope="col"><i class="fa fa-tasks"></i> Action </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>
