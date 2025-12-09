<?php
// verify_password.php
session_start();
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_password = $_POST['admin_password'] ?? '';
    $redirect_to = $_POST['redirect_to'] ?? 'manage_students.php';
    
    if ($entered_password === ADMIN_PASSWORD) {
        // Password correct - set admin session
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['password_authenticated_at'] = time();
        
        // Redirect to the requested page
        header("Location: " . $redirect_to);
        exit();
    } else {
        // Password incorrect
        $_SESSION['password_error'] = "Incorrect password. Please try again.";
        
        // Redirect back to the previous page
        header("Location: Login.php");
        exit();
    }
} else {
    // If not POST, redirect to login
    header("Location: Login.php");
    exit();
}
?>