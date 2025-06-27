<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Delete the "Remember me" cookie if it exists
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/'); // Expire the cookie by setting the time to the past
}

// Redirect to login page
header("Location: login.php");
exit();
?>
