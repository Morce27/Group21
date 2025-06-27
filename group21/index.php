<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Pagination logic
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get paginated results
$result = mysqli_query($con, "SELECT * FROM students WHERE deleted_at IS NULL ORDER BY id DESC LIMIT $limit OFFSET $offset");

// Get total number of active students for pagination
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM students WHERE deleted_at IS NULL");
$total_row = mysqli_fetch_assoc($total_query);
$total_students = $total_row['total'];
$total_pages = ceil($total_students / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Homepage</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" />
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
      max-width: 1400px;
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

    .btn-gold {
      background-color: #ffcc00;
      border-color: #ffcc00;
      color: #000;
    }

    .btn-gold:hover {
      background-color: #e6b800;
      border-color: #e6b800;
      color: #000;
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
      <h2>Student Records</h2>
      <div>
        <a href="add.php" class="btn btn-maroon me-2">Add New Student <b>+</b></a>
        <a href="deleted.php" class="btn btn-outline-dark">View Deleted Students</a>
        <a href="logout.php" class="btn btn-secondary">Logout</a>
      </div>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-info text-center"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <!-- Added page and limit params to form action -->
    <form method="POST" action="bulkDelete.php?page=<?= $page ?>&limit=<?= $limit ?>" onsubmit="return confirm('Are you sure you want to delete the selected students?');">
      <div class="table-responsive">
        <table class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all" /></th>
              <th>Student Number</th>
              <th>Name</th>
              <th>Email</th>
              <th>Mobile Number</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><input type="checkbox" name="selected_ids[]" value="<?= htmlspecialchars($row['id']) ?>" /></td>
                <td><?= htmlspecialchars($row['student_number']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['mobile_number']) ?></td>
                <td>
                  <a href="edit.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-sm btn-gold me-1">Edit</a>
                  <a href="delete.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-sm btn-maroon" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <div class="text-end mt-3">
        <button type="submit" class="btn btn-maroon">Delete Selected</button>
      </div>
    </form>

    <!-- Pagination & Limit Selector -->
    <div class="d-flex justify-content-between align-items-center mt-4">
      <form method="GET" class="d-flex align-items-center" id="limitForm">
        <label for="limit" class="me-2">Show</label>
        <select name="limit" id="limit" class="form-select me-2" onchange="updateLimit()" style="width: auto;">
          <option value="4" <?= $limit == 4 ? 'selected' : '' ?>>4</option>
          <option value="8" <?= $limit == 8 ? 'selected' : '' ?>>8</option>
          <option value="12" <?= $limit == 12 ? 'selected' : '' ?>>12</option>
        </select>
        <span>entries</span>
      </form>

      <nav>
        <ul class="pagination mb-0">
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    </div>
  </div>

  <script>
    // Save selected limit to localStorage and reload page
    function updateLimit() {
      const limit = document.getElementById('limit').value;
      localStorage.setItem('selectedLimit', limit);
      document.getElementById('limitForm').submit();
    }

    // Restore selected limit from localStorage on load
    window.addEventListener('DOMContentLoaded', function () {
      const savedLimit = localStorage.getItem('selectedLimit');
      const limitDropdown = document.getElementById('limit');

      if (savedLimit && limitDropdown) {
        if (limitDropdown.value !== savedLimit) {
          limitDropdown.value = savedLimit;
        }

        // If URL limit param doesn't match saved limit, reload with correct limit
        const url = new URL(window.location.href);
        if (url.searchParams.get("limit") !== savedLimit) {
          url.searchParams.set("limit", savedLimit);
          url.searchParams.set("page", 1); // reset to first page on limit change
          window.location.href = url.toString();
        }
      }
    });

    // Select all checkboxes
    document.getElementById('select-all').addEventListener('change', function () {
      const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
      checkboxes.forEach(cb => cb.checked = this.checked);
    });
  </script>
</body>
</html>
