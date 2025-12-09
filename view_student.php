<?php
// view_student.php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Same background as role.html */
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
        
        .view-container {
            position: relative;
            z-index: 2;
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(26, 26, 29, 0.85);
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(217, 4, 41, 0.25);
            border: 1px solid rgba(217, 4, 41, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .view-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .view-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            text-shadow: 0px 0px 12px rgba(217, 4, 41, 0.9);
            color: white;
        }
        
        .view-header h2 {
            font-size: 1rem;
            opacity: 0.9;
            color: #ccc;
        }
        
        /* Student Photo */
        .student-photo-large {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #d90429;
            box-shadow: 0 0 20px rgba(217, 4, 41, 0.5);
            margin: 0 auto 30px;
            display: block;
            transition: transform 0.3s ease;
        }
        
        .student-photo-large:hover {
            transform: scale(1.05);
        }
        
        /* Info Cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #d90429;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(217, 4, 41, 0.2);
        }
        
        .info-label {
            color: #aaa;
            font-size: 0.9rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .info-value {
            color: white;
            font-size: 1.2rem;
            font-weight: 500;
        }
        
        /* Button Container */
        .button-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .action-btn-large {
            padding: 15px 30px;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-width: 180px;
        }
        
        .btn-back {
            background-color: #6c757d;
            color: white;
            box-shadow: 0 0 10px rgba(108, 117, 125, 0.5);
        }
        
        .btn-edit {
            background-color: #ffc107;
            color: #000;
            box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
        }
        
        .btn-id {
            background-color: #d90429;
            color: white;
            box-shadow: 0 0 10px rgba(217, 4, 41, 0.6);
        }
        
        .btn-back:hover {
            background-color: #5a6268;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 0 15px rgba(108, 117, 125, 0.7);
        }
        
        .btn-edit:hover {
            background-color: #ffca2c;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 0 15px rgba(255, 193, 7, 0.7);
        }
        
        .btn-id:hover {
            background-color: #ff1e3c;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 0 15px rgba(217, 4, 41, 0.8);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .view-container {
                margin: 20px;
                padding: 20px;
            }
            
            .student-photo-large {
                width: 200px;
                height: 200px;
            }
            
            .button-container {
                flex-direction: column;
            }
            
            .action-btn-large {
                min-width: 100%;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="view-container">
    <div class="view-header">
        <h1><i class="fas fa-user-circle"></i> Student Profile</h1>
        <h2>Cebu Technological University - Main Campus</h2>
    </div>
    
    <img src="data:image/jpeg;base64,<?php echo base64_encode($student['photo']); ?>" 
         class="student-photo-large" 
         alt="<?php echo htmlspecialchars($student['full_name']); ?>">
    
    <div class="info-grid">
        <div class="info-card">
            <div class="info-label">ID Number</div>
            <div class="info-value"><?php echo htmlspecialchars($student['user_id']); ?></div>
        </div>
        
        <div class="info-card">
            <div class="info-label">Full Name</div>
            <div class="info-value"><?php echo htmlspecialchars($student['full_name']); ?></div>
        </div>
        
        <div class="info-card">
            <div class="info-label">Course</div>
            <div class="info-value"><?php echo htmlspecialchars($student['course']); ?></div>
        </div>
        
        <div class="info-card">
            <div class="info-label">Date of Birth</div>
            <div class="info-value"><?php echo date('F d, Y', strtotime($student['birthdate'])); ?></div>
        </div>
        
        <div class="info-card">
            <div class="info-label">Age</div>
            <div class="info-value">
                <?php 
                    $birthDate = new DateTime($student['birthdate']);
                    $today = new DateTime();
                    $age = $today->diff($birthDate)->y;
                    echo $age . ' years old';
                ?>
            </div>
        </div>
        
        <?php if (isset($student['created_at'])): ?>
        <div class="info-card">
            <div class="info-label">Registered Since</div>
            <div class="info-value"><?php echo date('F d, Y', strtotime($student['created_at'])); ?></div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="button-container">
        <a href="manage_students.php" class="action-btn-large btn-back">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="edit_student.php?id=<?php echo $student['user_id']; ?>" class="action-btn-large btn-edit">
            <i class="fas fa-edit"></i> Edit Profile
        </a>
        <a href="Picture.php" class="action-btn-large btn-id">
            <i class="fas fa-id-card"></i> View ID Card
        </a>
    </div>
</div>

</body>
</html>
<?php $conn->close(); ?>