<?php
$host = 'sql206.infinityfree.com';
$db   = 'nihao';
$user = 'if0_39338822';
$pass = '6c5V5pq2RBuu'; // Replace with your MySQL password

$con = new mysqli($host, $user, $pass, $db);

if ($con->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>