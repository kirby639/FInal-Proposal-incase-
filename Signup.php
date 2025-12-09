<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Signup</title>
  <link rel="stylesheet" href="style.css" />

  <style>

     .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #ecd6d6d3;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #af2f2fda;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
  </style>
</head>
<body>  

<section class="villian">
<div class="signup-container"> 
    <h1>STUDENT INFO SIGNUP</h1>

    <!-- IMPORTANT: form now submits to process_signup.php -->
    <form action="process_signup.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="ID">ID Number:</label>
            <input type="text" pattern="[0-9]*" maxlength="7" placeholder="7 characters" name="user_id" required />
        </div>

        <div class="form-group">
            <label for="fullName">Full Name:</label>  
            <input type="text" id="fullName" name="full_name" placeholder="Enter Full Name" required>
        </div>

        <div class="form-group">
            <label for="course">Course:</label>
                <select id="course" name="course" required>
                    <option value="">Select Course</option>
                    <option value="BS Information Technology">BS Information Technology</option>
                    <option value="BS Computer Science">BS Computer Science</option>
                    <option value="BS Computer Engineering">BS Computer Engineering</option>
                    <option value="BS Information Systems">BS Information Systems</option>
                    <option value="BS Education">BS Education</option>
                    <option value="BS Business Administration">BS Business Administration</option>
                </select>
        </div>

        <div class="form-group">
            <label for="Birth">Date of Birth:</label> 
            <input type="date" id="Birth" name="birthdate" required />
        </div>

        <label>Upload Image (2x2 Picture):</label><br>
        <input type="file" name="photo" accept="image/*" required>
        <br><br>

        <button class="login" type="submit">Register</button>

        <h2>
            <button class="home" onclick="window.location.href='Login.php'; return false;">Go back to login page</button>
        </h2>

    </form>

</div>
</section>
 <script>
        // Client-side validation for signup
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const userId = document.getElementById('user_id');
            const fullName = document.getElementById('full_name');
            const course = document.getElementById('course');
            const birthdate = document.getElementById('birthdate');
            const photo = document.getElementById('photo');
            
            // Validate ID
            if (!/^\d{7}$/.test(userId.value)) {
                alert('ID must be exactly 7 digits');
                userId.focus();
                e.preventDefault();
                return false;
            }
            
            // Validate name
            if (!/^[A-Za-z\s\'-]+$/.test(fullName.value)) {
                alert('Invalid name format');
                fullName.focus();
                e.preventDefault();
                return false;
            }
            
            // Validate course
            if (!course.value) {
                alert('Please select a course');
                e.preventDefault();
                return false;
            }
            
            // Validate birthdate
            const selectedDate = new Date(birthdate.value);
            const today = new Date();
            if (selectedDate >= today) {
                alert('Birthdate must be in the past');
                e.preventDefault();
                return false;
            }
            
            // Validate file
            if (photo.files.length > 0) {
                const file = photo.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                const maxSize = 2 * 1024 * 1024; // 2MB
                
                if (!validTypes.includes(file.type)) {
                    alert('Only JPG, PNG, and GIF images are allowed');
                    e.preventDefault();
                    return false;
                }
                
                if (file.size > maxSize) {
                    alert('File too large. Maximum size is 2MB');
                    e.preventDefault();
                    return false;
                }
            }
            
            return true;
        });
        
        // Format ID input
        document.getElementById('user_id').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 7) {
                this.value = this.value.substring(0, 7);
            }
        });
        
        // Set max birthdate to yesterday
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(today.getDate() - 1);
        document.getElementById('birthdate').max = yesterday.toISOString().split('T')[0];
    </script>
</body>
</html>
