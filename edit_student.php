<?php
// edit_student.php
session_start();

require_once "admin_auth.php";

// Check if admin password is required for this session
if (!requireAdminPassword()) {
    showPasswordForm();
}

require "db_connect.php";

// Get student ID from URL
$student_id = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : '';

// Fetch student data
$sql = "SELECT * FROM students WHERE user_id = '$student_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: manage_students.php?message=Student not found");
    exit();
}

$student = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_user_id = $conn->real_escape_string($_POST['old_user_id']);
    $new_user_id = $conn->real_escape_string($_POST['user_id']);
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $course = $conn->real_escape_string($_POST['course']);
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    
    // Check if new ID already exists (excluding current student)
    $check_sql = "SELECT user_id FROM students WHERE user_id = '$new_user_id' AND user_id != '$old_user_id'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        $error = "Error: Student ID '$new_user_id' already exists!";
    } else {
        // Handle photo update
        if (!empty($_FILES["photo"]["tmp_name"]) && file_exists($_FILES["photo"]["tmp_name"])) {
            $photo = addslashes(file_get_contents($_FILES["photo"]["tmp_name"]));
            $sql = "UPDATE students SET 
                    user_id = '$new_user_id',
                    full_name = '$full_name', 
                    course = '$course', 
                    birthdate = '$birthdate', 
                    photo = '$photo' 
                    WHERE user_id = '$old_user_id'";
        } else {
            $sql = "UPDATE students SET 
                    user_id = '$new_user_id',
                    full_name = '$full_name', 
                    course = '$course', 
                    birthdate = '$birthdate' 
                    WHERE user_id = '$old_user_id'";
        }
        
        if ($conn->query($sql) === TRUE) {
            $message = "Student updated successfully!";
            header("Location: manage_students.php?message=" . urlencode($message));
            exit();
        } else {
            $error = "Error updating record: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Same background as role.html */
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, #3a0f12 0%, #0b0b0c 80%);
            position: relative;
            overflow: scroll;
            padding: 20px;
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
        
        .edit-container {
            position: relative;
            z-index: 2;
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(26, 26, 29, 0.85);
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(217, 4, 41, 0.25);
            border: 1px solid rgba(217, 4, 41, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .edit-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .edit-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            text-shadow: 0px 0px 12px rgba(217, 4, 41, 0.9);
            color: white;
        }
        
        .edit-header h2 {
            font-size: 1rem;
            opacity: 0.9;
            color: #ccc;
        }
        
        .photo-preview-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .photo-preview {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #d90429;
            box-shadow: 0 0 15px rgba(217, 4, 41, 0.5);
            margin-bottom: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .photo-preview:hover {
            transform: scale(1.05);
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #e6e6e6;
        }
        
        .form-input, .form-select {
            width: 100%;
            padding: 12px 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid #ac4242cc;
            border-radius: 8px;
            font-size: 16px;
            color: white;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .form-input:focus, .form-select:focus {
            border-color: #d90429;
            outline: none;
            box-shadow: 0 0 10px rgba(217, 4, 41, 0.5);
        }
        
        .file-input-container {
            position: relative;
        }
        
        .file-input {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-label {
            display: block;
            padding: 12px 20px;
            background-color: rgba(217, 4, 41, 0.2);
            border: 1px dashed #d90429;
            border-radius: 8px;
            text-align: center;
            color: #e6e6e6;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-label:hover {
            background-color: rgba(217, 4, 41, 0.3);
            border-style: solid;
        }
        
        /* Button Container */
        .button-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .edit-btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            color: white;
            text-align: center;
        }
        
        .btn-update {
            background-color: #d90429;
            color: white;
            box-shadow: 0 0 10px rgba(217, 4, 41, 0.6);
        }
        
        .btn-cancel {
            background-color: #44151b;
            color: white;
            box-shadow: 0 0 10px rgba(191, 40, 40, 0.556);
        }
        
        .btn-update:hover {
            background-color: #ff1e3c;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 0 15px rgba(217, 4, 41, 0.8);
        }
        
        .btn-cancel:hover {
            background-color: #5a0c15;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 0 15px rgba(191, 40, 40, 0.7);
        }
        
        /* Error Message */
        .error-message {
            background: rgba(220, 53, 69, 0.2);
            color: #ea868f;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #dc3545;
            text-align: center;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .edit-container {
                margin: 20px;
                padding: 20px;
            }
            
            .button-container {
                flex-direction: column;
            }
            
            .photo-preview {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>

<div class="edit-container">
    <div class="edit-header">
        <h1><i class="fas fa-user-edit"></i> Edit Student Profile</h1>
        <h2>Cebu Technological University - Main Campus</h2>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="photo-preview-container">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($student['photo']); ?>" 
             class="photo-preview" 
             id="photoPreview"
             alt="Current Photo">
    </div>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="old_user_id" value="<?php echo htmlspecialchars($student['user_id']); ?>">
        
        <div class="form-group">
            <label class="form-label" for="user_id">ID Number:</label>
            <input type="text" id="user_id" name="user_id" 
                   value="<?php echo htmlspecialchars($student['user_id']); ?>" 
                   pattern="[0-9]{7}" 
                   minlength="7" 
                   maxlength="7"
                   required 
                   class="form-input"
                   title="7-digit number required">
            <small style="color: #aaa; font-size: 0.9rem;">Enter 7-digit student ID</small>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" 
                   value="<?php echo htmlspecialchars($student['full_name']); ?>" 
                   required class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label" for="course">Course:</label>
            <select id="course" name="course" required class="form-select">
                <option value="">Select Course</option>
                <option value="BS Information Technology" <?php echo ($student['course'] == 'BS Information Technology') ? 'selected' : ''; ?>>BS Information Technology</option>
                <option value="BS Computer Science" <?php echo ($student['course'] == 'BS Computer Science') ? 'selected' : ''; ?>>BS Computer Science</option>
                <option value="BS Computer Engineering" <?php echo ($student['course'] == 'BS Computer Engineering') ? 'selected' : ''; ?>>BS Computer Engineering</option>
                <option value="BS Information Systems" <?php echo ($student['course'] == 'BS Information Systems') ? 'selected' : ''; ?>>BS Information Systems</option>
                <option value="BS Education" <?php echo ($student['course'] == 'BS Education') ? 'selected' : ''; ?>>BS Education</option>
                <option value="BS Business Administration" <?php echo ($student['course'] == 'BS Business Administration') ? 'selected' : ''; ?>>BS Business Administration</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="birthdate">Date of Birth:</label>
            <input type="date" id="birthdate" name="birthdate" 
                   value="<?php echo htmlspecialchars($student['birthdate']); ?>" 
                   required class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Update Photo:</label>
            <div class="file-input-container">
                <input type="file" id="photo" name="photo" 
                       accept="image/*" class="file-input"
                       onchange="previewImage(this)">
                <label for="photo" class="file-label">
                    <i class="fas fa-camera"></i> Choose New Photo (Optional)
                </label>
            </div>
            <small style="color: #aaa; font-size: 0.9rem;">Leave empty to keep current photo</small>
        </div>
        
        <div class="button-container">
            <button type="submit" class="edit-btn btn-update">
                <i class="fas fa-save"></i> Update Student
            </button>
            <a href="manage_students.php" class="edit-btn btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Set max date for birthdate to yesterday
const today = new Date();
const yesterday = new Date(today);
yesterday.setDate(today.getDate() - 1);
document.getElementById('birthdate').max = yesterday.toISOString().split('T')[0];
</script>

</body>
</html>
<?php $conn->close(); ?>