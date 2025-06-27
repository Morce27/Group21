<?php
require_once("dbConnection.php");

if (isset($_POST['update'])) {
    // Sanitize inputs
    $id = $_POST['id'] ?? '';
    $studentnumber = $_POST['studentnumber'] ?? '';
    $name = $_POST['name'] ?? '';
    $mobilenumber = $_POST['mobilenumber'] ?? '';
    $email = $_POST['email'] ?? '';

    // Validate inputs
    $errors = [];
    if (empty($studentnumber)) {
        $errors[] = "Student Number field is empty.";
    }
    if (empty($name)) {
        $errors[] = "Name field is empty.";
    }
    if (empty($mobilenumber)) {
        $errors[] = "Mobile Number field is empty.";
    }
    if (empty($email)) {
        $errors[] = "Email field is empty.";
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<font color='red'>" . htmlspecialchars($error) . "</font><br/>";
        }
        echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
        exit;
    }

    // Prepare and execute update statement
    $stmt = $con->prepare("UPDATE students SET studentnumber = ?, name = ?, mobilenumber = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $studentnumber, $name, $mobilenumber, $email, $id);

    if ($stmt->execute()) {
        echo "<p><font color='green'>Data updated successfully!</font></p>";
        echo "<a href='index.php'>View Result</a>";
    } else {
        echo "<p><font color='red'>Error updating data: " . htmlspecialchars($stmt->error) . "</font></p>";
        echo "<a href='javascript:self.history.back();'>Go Back</a>";
    }

    $stmt->close();
} else {
    // If update button not clicked, redirect back to edit page or home
    header("Location: index.php");
    exit;
}
?>
