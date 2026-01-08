<?php
session_start();
if (!isset($_SESSION['user'])) {
  $_SESSION['flash_error'] = "Please log in first.";
  header("Location: login.php");
  exit();
}

// Load user data from JSON
$userData = null;
if (file_exists('users.json')) {
  $json = file_get_contents('users.json');
  $users = json_decode($json, true);

  foreach ($users as $user) {
    if ($user['fullname'] === $_SESSION['user']) {
      $userData = $user;
      break;
    }
  }
}

if (!$userData) {
  $_SESSION['flash_error'] = "User data not found.";
  header("Location: login.php");
  exit();
}

// Calculate age
$dob = new DateTime($userData['dob']);
$today = new DateTime();
$age = $today->diff($dob)->y;

// Handle logout
if (isset($_GET['logout'])) {
  $_SESSION['flash_success'] = "You are now logged out as " . $_SESSION['user'] . ".";
  session_destroy();
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="home">
  <?php if (isset($_SESSION['flash_success']) || isset($_SESSION['flash_error'])): ?>
    <div class="notification <?= isset($_SESSION['flash_success']) ? 'success' : 'error' ?>">
      <?= isset($_SESSION['flash_success']) ? htmlspecialchars($_SESSION['flash_success']) : htmlspecialchars($_SESSION['flash_error']); ?>
    </div>
    <?php unset($_SESSION['flash_success'], $_SESSION['flash_error']); ?>
  <?php endif; ?>

  <div class="container">
    <h2>Welcome, <?= htmlspecialchars($userData['fullname']) ?>!</h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($userData['email']) ?></p>
    <p><strong>Gender:</strong> <?= htmlspecialchars($userData['gender']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($userData['address']) ?></p>
    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($userData['dob']) ?></p>
    <p><strong>Age:</strong> <?= $age ?> years old</p>
    <a href="?logout=true">Logout</a>
  </div>
</body>
</html>
