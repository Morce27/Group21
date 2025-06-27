<?php
session_start();
require_once("dbConnection.php");

// Redirect if user not logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $deleted_at = date('Y-m-d H:i:s');

    $result = mysqli_query($con, "UPDATE students SET deleted_at='$deleted_at' WHERE id=$id");

    if ($result) {
        $_SESSION['message'] = "Student deleted successfully.";
    } else {
        $_SESSION['message'] = "Error: Unable to delete student.";
    }
} else {
    $_SESSION['message'] = "No student ID provided.";
}

// Preserve pagination query parameters (default fallback)
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 4;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Redirect back to homepage with limit and page preserved
header("Location: index.php?page=$page&limit=$limit");
exit();
?>
