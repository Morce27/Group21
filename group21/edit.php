<?php
require_once("dbConnection.php"); // Your DB connection file

// Initialize variables
$id = $_GET['id'] ?? null;
$studentnumber = $name = $mobilenumber = $email = '';

if ($id) {
    // Prepare statement to fetch existing data
    $stmt = $con->prepare("SELECT student_number, name, mobile_number, email FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($studentnumber, $name, $mobilenumber, $email);
    $stmt->fetch();
    $stmt->close();
    
    if (!$studentnumber) {
        echo "No student found with ID: " . htmlspecialchars($id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo isset($id) ? 'Edit Data' : 'Add Data'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Your existing CSS styles here */
    body {
      background: linear-gradient(to bottom right, #800000, #330000);
      color: #333;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .card {
      width: 100%;
      max-width: 600px;
      background-color: #f0f0f0;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
      border: none;
    }
    .card-header {
      background-color: transparent;
      border-bottom: 1px solid #ccc;
      font-size: 1.5rem;
      font-weight: bold;
      text-align: center;
      color: #800000;
    }
    .form-label {
      color: #333;
      font-weight: bold;
    }
    .form-control {
      background-color: #fff;
      border: 1px solid #ccc;
      color: #333;
    }
    .btn-maroon {
      background-color: #800000;
      border: none;
      color: #fff;
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
    <div class="card-header">
      <?php echo isset($id) ? 'Edit Student Record' : 'Add New Student'; ?>
    </div>
    <div class="card-body">
      <form method="post" action="<?php echo isset($id) ? 'editAction.php' : 'addAction.php'; ?>">
        <div class="mb-3">
          <label for="studentnumber" class="form-label">Student Number</label>
          <input type="text" class="form-control" name="studentnumber" id="studentnumber" value="<?php echo htmlspecialchars($studentnumber); ?>" required>
        </div>

        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>

        <div class="mb-3">
          <label for="mobilenumber" class="form-label">Mobile Number</label>
          <input type="text" class="form-control" name="mobilenumber" id="mobilenumber" value="<?php echo htmlspecialchars($mobilenumber); ?>" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <?php if (isset($id)): ?>
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center">
          <a href="index.php" class="text-decoration-none">&larr; Back to Home</a>
          <button type="submit" name="<?php echo isset($id) ? 'update' : 'submit'; ?>" class="btn btn-maroon">
            <?php echo isset($id) ? 'Update' : 'Add'; ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
