<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Data</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(to bottom right, #800000, #330000);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .card {
      background-color: #f0f0f0;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      padding: 30px;
      width: 100%;
      max-width: 500px;
    }

    .card h2 {
      text-align: center;
      color: #800000;
      margin-bottom: 20px;
    }

    .btn-primary {
      background-color: #800000;
      border-color: #800000;
    }

    .btn-primary:hover {
      background-color: #660000;
      border-color: #660000;
    }

    a.back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #800000;
      text-decoration: none;
    }

    a.back-link:hover {
      text-decoration: underline;
    }

    label {
      font-weight: bold;
      color: #333;
    }
  </style>
</head>
<body>

  <div class="card">
    <h2>Add Student Data</h2>
    <form action="addAction.php" method="post" name="add">
      <div class="mb-3">
        <label for="studentnumber">Student Number</label>
        <input type="text" class="form-control" name="studentnumber" required>
      </div>
      <div class="mb-3">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" required>
      </div>
      <div class="mb-3">
        <label for="mobilenumber">Mobile Number</label>
        <input type="text" class="form-control" name="mobilenumber" required>
      </div>
      <div class="mb-3">
        <label for="email">Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Add</button>
    </form>
    <a href="index.php" class="back-link">&larr; Back to Home</a>
  </div>

</body>
</html>
