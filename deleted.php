<?php
session_start();
require_once("dbConnection.php");

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$deleted = mysqli_query($con, "SELECT * FROM students WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recently Deleted Students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom right, #800000, #330000);
      min-height: 100vh;
      padding: 40px 0;
    }

    .container {
      background-color: #f0f0f0;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      max-width: 1300px; /* Increased width from 1200px */
    }

    .table {
      min-width: 900px; /* Added minimum width for table */
    }

    h2 {
      color: #800000;
      margin-bottom: 25px;
      text-align: center;
    }

    .btn-maroon {
      background-color: #800000;
      border-color: #800000;
      color: #fff;
    }

    .btn-maroon:hover {
      background-color: #660000;
      border-color: #660000;
    }

    .table thead {
      background-color: #800000;
      color: #fff;
    }

    .table td, .table th {
      vertical-align: middle;
    }

    .table-striped > tbody > tr:nth-of-type(even) {
      background-color: #f9f9f9;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    @media (max-width: 576px) {
      .top-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="container mx-auto">
    <div class="top-bar">
      <h2>Recently Deleted Students</h2>
      <div>
        <a href="index.php" class="btn btn-secondary me-2">Back to Homepage</a>
        <a href="logout.php" class="btn btn-outline-dark">Logout</a>
      </div>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-info text-center"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <form method="POST" action="restore.php" onsubmit="return confirm('Restore selected students?');">
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all"></th>
              <th>Student Number</th>
              <th>Name</th>
              <th>Email</th>
              <th>Mobile Number</th>
              <th>Deleted At</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($deleted)): ?>
              <tr>
                <td><input type="checkbox" name="restore_ids[]" value="<?= $row['id'] ?>"></td>
                <td><?= htmlspecialchars($row['student_number']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['mobile_number']) ?></td>
                <td><?= htmlspecialchars($row['deleted_at']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <div class="text-end mt-3">
        <button type="submit" class="btn btn-maroon">Restore Selected</button>
      </div>
    </form>
  </div>

  <script>
    document.getElementById('select-all').addEventListener('change', function () {
      const checkboxes = document.querySelectorAll('input[name="restore_ids[]"]');
      checkboxes.forEach(cb => cb.checked = this.checked);
    });
  </script>
</body>
</html>
