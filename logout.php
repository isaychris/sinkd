<?php

/*
//===================================================================================================
// File: logout.php
// Description: This file is used to logout from our website. The session_data is destroyed.
//===================================================================================================
*/

session_start();
session_unset();
session_destroy();
header("Location: login.php");
?>
