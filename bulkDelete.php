<?php
session_start();
require_once("dbConnection.php");

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Process deletion if selected_ids is present
if (isset($_POST['selected_ids']) && is_array($_POST['selected_ids'])) {
    $ids = array_map('intval', $_POST['selected_ids']);  // Sanitize IDs
    $id_list = implode(',', $ids);  // Turn into comma-separated list

    // Soft delete by setting deleted_at timestamp
    $query = "UPDATE students SET deleted_at = NOW() WHERE id IN ($id_list)";
    
    if (mysqli_query($con, $query)) {
        $_SESSION['message'] = count($ids) . " student(s) deleted (soft delete).";
    } else {
        $_SESSION['message'] = "Error deleting students.";
    }
} else {
    $_SESSION['message'] = "No students selected.";
}

// Preserve pagination query parameters or default values
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 4;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Redirect back to homepage with pagination params preserved
header("Location: index.php?page=$page&limit=$limit");
exit();
