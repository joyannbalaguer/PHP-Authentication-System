<?php
session_start();

$message = '';
$fullname = '';
$email = '';
$dob = '';
$address = '';
$gender = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullname = trim($_POST['fullname']);
  $email = strtolower(trim($_POST['email']));
  $password = trim($_POST['password']);
  $dob = $_POST['dob'];
  $address = trim($_POST['address']);
  $gender = $_POST['gender'];

  $today = new DateTime();
  $birth = new DateTime($dob);
  $age = $today->diff($birth)->y;

  if ($birth > $today) {
    $message = "Invalid date of birth. You cannot be born in the future.";
  } elseif ($age < 5 || $age > 100) {
    $message = "Invalid age. Please enter a valid date of birth.";
  } elseif (!preg_match("/^[a-zA-Z\s.]+$/", $fullname)) {
    $message = "Full name must contain only letters, spaces, or periods.";
  } elseif (!preg_match("/^[a-z0-9._%+-]+@(gmail|yahoo)\.com$/", $email)) {
    $message = "Email must end with @gmail.com or @yahoo.com.";
  } else {
    $users = [];
    if (file_exists('users.json')) {
      $json = file_get_contents('users.json');
      $users = json_decode($json, true);
    }

    foreach ($users as $user) {
      if ($user['email'] == $email) {
        $message = "Email already registered.";
        break;
      }
    }

    if (!$message) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $users[] = [
        'fullname' => $fullname,
        'email' => $email,
        'password' => $hashed_password,
        'dob' => $dob,
        'address' => $address,
        'gender' => $gender
      ];
      file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));

      $_SESSION['flash_success'] = "Signup successful! You can now log in.";
      header("Location: login.php");
      exit();
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sign Up</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="signup">
  <?php if (isset($_SESSION['flash_success']) || isset($_SESSION['flash_error'])): ?>
    <div class="notification <?= isset($_SESSION['flash_success']) ? 'success' : 'error' ?>">
      <?= isset($_SESSION['flash_success']) ? htmlspecialchars($_SESSION['flash_success']) : htmlspecialchars($_SESSION['flash_error']); ?>
    </div>
    <?php unset($_SESSION['flash_success'], $_SESSION['flash_error']); ?>
  <?php endif; ?>

  <div class="form-container">
    <h2>Create Account</h2>

    <?php if ($message): ?>
      <div class="alert error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
      <label>Full Name</label>
      <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required>

      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <label>Date of Birth</label>
      <input type="date" name="dob" value="<?= htmlspecialchars($dob) ?>" required>

      <label>Address</label>
      <input type="text" name="address" value="<?= htmlspecialchars($address) ?>" required>

      <label>Gender</label>
      <select name="gender" required>
        <option value="">-- Select Gender --</option>
        <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
        <option value="Other" <?= $gender == 'Other' ? 'selected' : '' ?>>Other</option>
      </select>

      <input type="submit" value="Sign Up">
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
</body>
</html>
