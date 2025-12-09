<?php
// admin_auth.php

// Check if admin password session has expired
if (isset($_SESSION['password_authenticated_at'])) {
    $timeout = 30 * 60; // 30 minutes in seconds
    if (time() - $_SESSION['password_authenticated_at'] > $timeout) {
        unset($_SESSION['admin_authenticated']);
        unset($_SESSION['password_authenticated_at']);
    }
}

require_once "config.php";

function requireAdminPassword() {
    // Check if admin is already authenticated
    if (isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true) {
        return true; // Already authenticated
    }
    
    return false;
}

function showPasswordForm() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Authentication Required</title>
        <link rel="stylesheet" href="style.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            body {
                min-height: 100vh;
                background: radial-gradient(circle at top, #3a0f12 0%, #0b0b0c 80%);
                position: relative;
                overflow-x: hidden;
            }
            
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('https://wallpapers.com/images/hd/akatsuki-logo-red-cloud-pattern-bulw8ch6y6nrolpi.jpg');
                background-size: cover;
                opacity: 0.08;
                background-repeat: no-repeat;
                background-position: center;
                pointer-events: none;
                z-index: 0;
            }
            
            .password-container {
                position: relative;
                z-index: 2;
                max-width: 400px;
                margin: 100px auto;
                padding: 40px;
                background: rgba(26, 26, 29, 0.95);
                border-radius: 15px;
                box-shadow: 0 0 30px rgba(217, 4, 41, 0.4);
                border: 1px solid rgba(217, 4, 41, 0.3);
                backdrop-filter: blur(10px);
                text-align: center;
            }
            
            .password-container h1 {
                color: white;
                margin-bottom: 30px;
                text-shadow: 0px 0px 12px rgba(217, 4, 41, 0.9);
                font-size: 1.8rem;
            }
            
            .password-form .form-group {
                margin-bottom: 25px;
                text-align: left;
            }
            
            .password-form label {
                display: block;
                margin-bottom: 10px;
                color: #e6e6e6;
                font-weight: 500;
            }
            
            .password-form input {
                width: 100%;
                padding: 15px;
                background-color: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(172, 66, 66, 0.8);
                border-radius: 8px;
                font-size: 16px;
                color: white;
                transition: all 0.3s ease;
            }
            
            .password-form input:focus {
                border-color: #d90429;
                outline: none;
                box-shadow: 0 0 15px rgba(217, 4, 41, 0.5);
            }
            
            .password-btn {
                width: 100%;
                padding: 16px;
                border: none;
                border-radius: 30px;
                background-color: #d90429;
                color: white;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 0 12px rgba(217, 4, 41, 0.6);
                margin-top: 10px;
            }
            
            .password-btn:hover {
                background-color: #ff1e3c;
                transform: translateY(-3px);
                box-shadow: 0 0 20px rgba(217, 4, 41, 0.8);
            }
            
            .error-message {
                background: rgba(220, 53, 69, 0.2);
                color: #ea868f;
                padding: 12px;
                border-radius: 8px;
                margin-bottom: 20px;
                border: 1px solid #dc3545;
                text-align: center;
                animation: fadeIn 0.3s ease;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .password-info {
                margin-top: 20px;
                color: #aaa;
                font-size: 0.9rem;
            }
            
            .back-button {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background: rgba(217, 4, 41, 0.2);
                color: #e6e6e6;
                text-decoration: none;
                border-radius: 5px;
                transition: all 0.3s ease;
                border: 1px solid rgba(217, 4, 41, 0.3);
            }
            
            .back-button:hover {
                background: rgba(217, 4, 41, 0.4);
                color: white;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <div class="password-container">
            <h1><i class="fas fa-lock"></i> Admin Authentication Required</h1>
            
            <?php 
            if (isset($_SESSION['password_error'])) {
                echo '<div class="error-message"><i class="fas fa-exclamation-circle"></i> ' . $_SESSION['password_error'] . '</div>';
                unset($_SESSION['password_error']);
            }
            ?>
            
            <form method="POST" action="verify_password.php" class="password-form">
                <input type="hidden" name="redirect_to" value="manage_students.php">
                
                <div class="form-group">
                    <label for="admin_password">
                        <i class="fas fa-key"></i> Enter Admin Password:
                    </label>
                    <input type="password" id="admin_password" name="admin_password" required autofocus>
                </div>
                
                <button type="submit" class="password-btn">
                    <i class="fas fa-unlock"></i> Authenticate & Continue
                </button>
            </form>
            
            <a href="Login.php" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
            
            <div class="password-info">
                <i class="fas fa-info-circle"></i> This action requires administrator privileges.
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>