<?php 
session_start();
require_once("dbConnection.php");

// Only allow logged-in users
if (!isset($_SESSION['username'])) {
    $_SESSION['message'] = "Access denied. Please log in.";
    header("Location: homepage.php");
    exit();
}

if (isset($_POST['restore_ids']) && is_array($_POST['restore_ids'])) {
    $ids = array_map('intval', $_POST['restore_ids']);
    $id_list = implode(',', $ids);

    $query = "UPDATE students SET deleted_at = NULL WHERE id IN ($id_list)";
    if (mysqli_query($con, $query)) {
        $_SESSION['message'] = count($ids) . " student(s) restored successfully.";
    } else {
        $_SESSION['message'] = "Error restoring students.";
    }
}

header("Location: deleted.php");
exit();
