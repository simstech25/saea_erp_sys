<?php
require_once '../src/bootstrap.php';

// Handle signup form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $message = "Passwords do not match!";
    } else {
        $success = $users->addUser($fullName, $username, $email, $password);
        if ($success) {
            $message = "Signup successful! Await admin approval.";
        } else {
            $message = "Signup failed! Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - SAEa ERP</title>
  <link rel="stylesheet" href="../public/css/stylesheet.css">
</head>
<body>

<div class="signup-box">
    <img src="../public/images/saea_image.jpg" class="company-logo" alt="Company Logo">
    <h2>Sign Up</h2>

    <?php if($message): ?>
      <p style="color:red;text-align:center;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">

        <!-- Full Name -->
        <div class="input-group">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-5.33 0-8 2.667-8 4v2h16v-2c0-1.333-2.67-4-8-4z"/>
            </svg>
            <input type="text" name="fullname" required>
            <label>Full Name</label>
        </div>

        <!-- Username -->
        <div class="input-group">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-5.33 0-8 2.667-8 4v2h16v-2c0-1.333-2.67-4-8-4z"/>
            </svg>
            <input type="text" name="username" required>
            <label>Username</label>
        </div>

        <!-- Email -->
        <div class="input-group">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5z"/>
            </svg>
            <input type="email" name="email" required>
            <label>Email</label>
        </div>

        <!-- Password -->
        <div class="input-group">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 1.5a6 6 0 0 0-6 6v2H5.25A1.75 1.75 0 0 0 3.5 11.25v9.5A1.75 1.75 0 0 0 5.25 22.5h13.5A1.75 1.75 0 0 0 20.5 20.75v-9.5a1.75 1.75 0 0 0-1.75-1.75H18V7.5a6 6 0 0 0-6-6Z"/>
            </svg>

            <input type="password" name="password" id="signup-password" required>
            <label>Password</label>

            <span class="toggle"
                  onclick="togglePassword('signup-password','eye-open-sign','eye-closed-sign')">

                <svg id="eye-open-sign" class="eye-icon" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7z"/>
                    <circle cx="12" cy="12" r="2.5"/>
                </svg>

                <svg id="eye-closed-sign" class="eye-icon" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24" fill="currentColor" style="display:none">
                    <path d="M1 1l22 22"/>
                </svg>
            </span>
        </div>

        <!-- Confirm Password -->
        <div class="input-group">
            <input type="password" name="confirm_password" id="confirm-password" required>
            <label>Confirm Password</label>
        </div>

        <button type="submit">Sign Up</button>
        <h3>Already have an account? <a href="login.php">Login Here</a></h3>

    </form>
</div>

<script src="../public/js/script.js"></script>
</body>
</html>
