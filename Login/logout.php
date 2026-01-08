<?php
session_start();
$name = $_SESSION['fullname'] ?? '';
session_unset();
session_destroy();

session_start();
$_SESSION['flash_wsuccess'] = "You are now logged out, $name.";
header("Location: login.php");
exit();
