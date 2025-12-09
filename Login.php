<?php
session_start();

// If already logged in, go directly to ID card
if (isset($_SESSION["loggedInUser"])) {
    header("Location: Picture.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Login</title>
<link rel="stylesheet" href="style.css" />
</head>
<body class="Login">

<nav class="navbar">
    <div class="nav-container">
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>

<section class="villian">
<div class="login-container">
<h1>Student Info Login</h1>

<form action="process_login.php" method="POST">
    <div class="form-group">
        <label for="ID">ID Number:</label>
        <input type="text" id="ID" name="user_id" pattern="[0-9]*" minlength="7" maxlength="7" placeholder="7 characters" required />
    </div>

    <div class="form-group">
        <label for="fullName">Full Name:</label>
        <input type="text" id="fullName" name="full_name" placeholder="Enter Full Name" required />
    </div>

    <button type="submit" class="login">Login</button>
</form>

<div class="form-footer">
    <button class="go-back" onclick="window.location.href='role.html'">Go back to home page</button>
    <p>Don't have an account? 
        <button class="register" onclick="window.location.href='Signup.php'">Register here</button>
    </p>
    <button class="register" onclick="window.location.href='manage_students.php'">
        <i class="fas fa-users-cog"></i> Manage Students (Admin)
    </button>
</div>
</div>
</section>

<script>
// Menu JS (kept from your original HTML)
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

if (hamburger) {
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        if (navMenu) navMenu.classList.toggle('active');
    });
}
</script>

</body>
</html>
