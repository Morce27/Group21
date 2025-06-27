<?php
session_start();
require 'db.php';

$username = $password = $confirm_password = "";
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username)) {
        $errors[] = "Username is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        $username = mysqli_real_escape_string($con, $username);
        $password = mysqli_real_escape_string($con, $password);

        $check_query = "SELECT * FROM users WHERE username = '$username'";
        $check_result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $errors[] = "Username is already taken";
        } else {
            $hashed_password = hash('sha256', $password);
            $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($con, $insert_query)) {
                $success = "Registration successful. You may now <a href='login.php'>log in</a>.";
                $username = $password = $confirm_password = "";
            } else {
                $errors[] = "Error while registering. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom right, #800000, #330000);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      background-color: #f0f0f0; /* light gray box */
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);
      padding: 20px;
      width: 100%;
      max-width: 500px;
      border: none;
    }

    .card-header {
      background-color: transparent;
      text-align: center;
      font-size: 1.6rem;
      font-weight: bold;
      color: #800000;
      border-bottom: 1px solid #ccc;
      margin-bottom: 20px;
    }

    .form-label {
      font-weight: bold;
      color: #333;
    }

    .form-control {
      background-color: #fff;
      border: 1px solid #ccc;
      color: #333;
    }

    .btn-maroon {
      background-color: #800000;
      color: #fff;
      border: none;
    }

    .btn-maroon:hover {
      background-color: #660000;
    }

    a {
      color: #800000;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="card-header">Create Account</div>
    <div class="card-body">
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
              <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php elseif ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-maroon w-100">Register</button>
        <div class="text-center mt-3">
          <a href="login.php">Already have an account?</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
