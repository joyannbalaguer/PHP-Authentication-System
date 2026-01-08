<?php
session_start();

$message = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = strtolower(trim($_POST['email']));
  $password = trim($_POST['password']);

  if (file_exists('users.json')) {
    $json = file_get_contents('users.json');
    $users = json_decode($json, true);

    $found = false;
    foreach ($users as $user) {
      if ($user['email'] == $email && password_verify($password, $user['password'])) {
        $found = true;
        $_SESSION['user'] = $user['fullname'];
        $_SESSION['flash_success'] = "Welcome back, " . $user['fullname'] . "! You are now logged in.";
        header("Location: home.php");
        exit();
      }
    }

    if (!$found) {
      $message = "Invalid email or password.";
    }
  } else {
    $message = "No registered users yet.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php if (isset($_SESSION['flash_success']) || isset($_SESSION['flash_error'])): ?>
    <div class="notification <?= isset($_SESSION['flash_success']) ? 'success' : 'error' ?>">
      <?= isset($_SESSION['flash_success']) ? htmlspecialchars($_SESSION['flash_success']) : htmlspecialchars($_SESSION['flash_error']); ?>
    </div>
    <?php unset($_SESSION['flash_success'], $_SESSION['flash_error']); ?>
  <?php endif; ?>

  <div class="form-container">
    <h2>Login</h2>

    <?php if ($message): ?>
      <div class="alert error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
  </div>
</body>
</html>
