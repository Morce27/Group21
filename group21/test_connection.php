<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<?php
$servername = "sql111.infinityfree.com"; // Replace with your host from InfinityFree
$username = "if0_39326601";      // Replace with your InfinityFree MySQL username
$password = "if0_39338822";      // Your MySQL password
$dbname = "6c5V5pq2RBuu";

$con = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully!";
}
?>