<?php
require_once '../src/bootstrap.php';
$users = new Users($db);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $users->login($username, $password);

    if ($result === true) {
        header("Location: dashboard.php");
        exit;
    } elseif ($result === 'pending') {
        $message = "Account pending approval!";
    } else {
        $message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SAEa ERP</title>
  <link rel="stylesheet" href="../public/css/stylesheet.css">
</head>
<body>

<div class="login-box">
    <img src="../public/images/saea_image.jpg" class="company-logo" alt="Company Logo">
    <h2>Login Here</h2>

    <?php if($message): ?>
      <p style="color:red;text-align:center;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">

        <!-- Username -->
        <div class="input-group">
            <!-- User icon -->
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-5.33 0-8 2.667-8 4v2h16v-2c0-1.333-2.67-4-8-4z"/>
            </svg>

            <input type="text" name="username" required>
            <label>Username</label>
        </div>

        <!-- Password -->
        <div class="input-group">
            <!-- Lock icon -->
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 1.5a6 6 0 0 0-6 6v2H5.25A1.75 1.75 0 0 0 3.5 11.25v9.5A1.75 1.75 0 0 0 5.25 22.5h13.5A1.75 1.75 0 0 0 20.5 20.75v-9.5a1.75 1.75 0 0 0-1.75-1.75H18V7.5a6 6 0 0 0-6-6Zm-3 6a3 3 0 1 1 6 0v2H9V7.5Z"/>
            </svg>

            <input type="password" name="password" id="login-password" required>
            <label>Password</label>

            <!-- Eye toggle -->
            <span class="toggle"
                  onclick="togglePassword('login-password','eye-open','eye-closed')">

              <!-- Eye open -->
              <svg id="eye-open" class="eye-icon" xmlns="http://www.w3.org/2000/svg"
                   viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                <circle cx="12" cy="12" r="2.5"/>
              </svg>

              <!-- Eye closed -->
              <svg id="eye-closed" class="eye-icon" xmlns="http://www.w3.org/2000/svg"
                   viewBox="0 0 24 24" fill="currentColor" style="display:none">
                <path d="M1 1l22 22"/>
                <path d="M12 5c-7.633 0-12 7-12 7a15.3 15.3 0 0 0 4.5 5.5"/>
              </svg>

            </span>
        </div>

        <button type="submit">Login</button>
        <h3>Don't have an account? <a href="signup.php">Signup Here</a></h3>
    </form>
</div>

<script src="js/script.js"></script>

</body>
</html>
