<?php
// logout_admin.php
session_start();

// Clear admin authentication ONLY
unset($_SESSION['admin_authenticated']);
unset($_SESSION['password_authenticated_at']);

// Do NOT clear student session
// Keep $_SESSION["loggedInUser"] if student is logged in

// Redirect to home page
header("Location: Login.php");
exit();
?>