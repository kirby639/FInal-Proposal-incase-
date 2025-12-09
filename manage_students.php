<?php
// manage_students.php
session_start();
require_once "admin_auth.php";

// Check if admin password is required for this session
if (!requireAdminPassword()) {
    showPasswordForm();
}

// NO STUDENT LOGIN CHECK HERE - Admin access is independent

// Database connection
require "db_connect.php";

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM students WHERE user_id = '$delete_id'";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Student deleted successfully!";
        header("Location: manage_students.php?message=" . urlencode($message));
        exit();
    } else {
        $error = "Error deleting record: " . $conn->error;
    }
}

// Fetch all students
$sql = "SELECT * FROM students ORDER BY full_name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Same background as role.html */
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, #3a0f12 0%, #0b0b0c 80%);
            position: relative;
            overflow-x: hidden;
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
        
        .management-container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .management-header {
            text-align: center;
            margin-bottom: 30px;
            padding-top: 20px;
        }
        
        .management-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0px 0px 12px rgba(217, 4, 41, 0.9);
            color: white;
        }
        
        .management-header h2 {
            font-size: 1.2rem;
            opacity: 0.9;
            color: #ccc;
        }
        
        .navigation-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .nav-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 180px;
            justify-content: center;
            text-decoration: none;
            color: white;
        }
        
        .nav-btn-primary {
            background-color: #d90429;
            box-shadow: 0 0 10px rgba(217, 4, 41, 0.6);
        }
        
        .nav-btn-secondary {
            background-color: #44151b;
            box-shadow: 0 0 10px rgba(191, 40, 40, 0.556);
        }
        
        .nav-btn-primary:hover, .nav-btn-secondary:hover {
            transform: translateY(-6px) scale(1.08);
            box-shadow: 0 0 15px rgba(217, 4, 41, 0.8),
                        0 0 30px rgba(217, 4, 41, 0.6),
                        0 0 45px rgba(217, 4, 41, 0.4);
        }
        
        .nav-btn-primary:hover {
            background-color: #ff1e3c;
        }
        
        .nav-btn-secondary:hover {
            background-color: #080506;
        }
        
        /* Admin Status */
        .admin-status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #4CAF50;
            font-size: 0.9rem;
        }
        
        /* Table Container */
        .table-container {
            background: rgba(26, 26, 29, 0.85);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 25px rgba(217, 4, 41, 0.25);
            border: 1px solid rgba(217, 4, 41, 0.2);
            backdrop-filter: blur(10px);
        }
        
        /* Student Photo */
        .student-photo {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid #d90429;
            transition: transform 0.3s ease;
        }
        
        .student-photo:hover {
            transform: scale(1.5);
            z-index: 10;
            position: relative;
        }
        
        /* Table Styling */
        .student-table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }
        
        .student-table th {
            background: rgba(217, 4, 41, 0.3);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
            border-bottom: 2px solid #d90429;
        }
        
        .student-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .student-table tr:hover {
            background: rgba(217, 4, 41, 0.1);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            white-space: nowrap;
        }
        
        .action-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            min-width: 90px;
            justify-content: center;
            text-decoration: none;
            color: white;
        }
        
        .btn-view {
            background-color: #0d6efd;
            box-shadow: 0 0 8px rgba(13, 110, 253, 0.5);
        }
        
        .btn-edit {
            background-color: #ffc107;
            color: #000;
            box-shadow: 0 0 8px rgba(255, 193, 7, 0.5);
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
            box-shadow: 0 0 8px rgba(220, 53, 69, 0.5);
        }
        
        .btn-view:hover {
            background-color: #0b5ed7;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 0 15px rgba(13, 110, 253, 0.7);
        }
        
        .btn-edit:hover {
            background-color: #ffca2c;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 0 15px rgba(255, 193, 7, 0.7);
        }
        
        .btn-delete:hover {
            background-color: #bb2d3b;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 0 15px rgba(220, 53, 69, 0.7);
        }
        
        /* Message Alerts */
        .message-alert {
            padding: 15px 25px;
            border-radius: 30px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
            animation: fadeIn 0.5s ease;
        }
        
        .alert-success {
            background: rgba(21, 87, 36, 0.3);
            color: #75b798;
            border: 1px solid #2e8b57;
            box-shadow: 0 0 10px rgba(46, 139, 87, 0.3);
        }
        
        .alert-error {
            background: rgba(114, 28, 36, 0.3);
            color: #ea868f;
            border: 1px solid #dc3545;
            box-shadow: 0 0 10px rgba(220, 53, 69, 0.3);
        }
        
        .alert-info {
            background: rgba(13, 110, 253, 0.2);
            color: #9ec5fe;
            border: 1px solid #0d6efd;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* No Records */
        .no-records {
            text-align: center;
            padding: 40px;
            color: #aaa;
            font-size: 1.1rem;
        }
        
        /* Admin Logout Button */
        .admin-logout-btn {
            padding: 12px 25px;
            background: #44151b;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 0 10px rgba(191, 40, 40, 0.556);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .admin-logout-btn:hover {
            background: #080506;
            transform: translateY(-3px);
            box-shadow: 0 0 15px rgba(191, 40, 40, 0.8);
        }
        
        /* Go to Login Button */
        .go-to-login {
            padding: 12px 25px;
            background: #d90429;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 0 10px rgba(217, 4, 41, 0.6);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }
        
        .go-to-login:hover {
            background: #ff1e3c;
            transform: translateY(-3px);
            box-shadow: 0 0 15px rgba(217, 4, 41, 0.8);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .management-header h1 {
                font-size: 1.8rem;
            }
            
            .student-table {
                display: block;
                overflow-x: auto;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn {
                min-width: 70px;
                padding: 6px 10px;
                font-size: 0.8rem;
            }
            
            .nav-btn {
                min-width: 150px;
                padding: 10px 15px;
            }
            
            .navigation-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<div class="management-container">
    <div class="management-header">
        <h1>📋 Student Management System</h1>
        <h2>Cebu Technological University - Main Campus</h2>
    </div>
    
    <!-- Admin Status -->
    <div class="admin-status">
        <i class="fas fa-shield-alt"></i> Admin Authenticated
    </div>
    
    <!-- Navigation Buttons -->
    <div class="navigation-buttons">
        <button onclick="window.location='Picture.php'" class="nav-btn nav-btn-primary">
            <i class="fas fa-id-card"></i> View ID Card
        </button>
        <button onclick="window.location='Signup.php'" class="nav-btn nav-btn-secondary">
            <i class="fas fa-user-plus"></i> Add New Student
        </button>
        <button onclick="window.location='Login.php'" class="nav-btn nav-btn-primary">
            <i class="fas fa-sign-in-alt"></i> Student Login
        </button>
        <a href="logout_admin.php" class="nav-btn nav-btn-secondary">
            <i class="fas fa-sign-out-alt"></i> Admin Logout
        </a>
    </div>
    
    <!-- Messages -->
    <?php if (isset($_GET['message'])): ?>
        <div class="message-alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="message-alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <!-- Students Table -->
    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
            <table class="student-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>ID Number</th>
                        <th>Full Name</th>
                        <th>Course</th>
                        <th>Birthdate</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['photo']); ?>" 
                                     class="student-photo" 
                                     alt="<?php echo htmlspecialchars($row['full_name']); ?>"
                                     title="Click to enlarge">
                            </td>
                            <td><strong><?php echo htmlspecialchars($row['user_id']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course']); ?></td>
                            <td><?php echo date('F d, Y', strtotime($row['birthdate'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="view_student.php?id=<?php echo $row['user_id']; ?>" 
                                       class="action-btn btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="edit_student.php?id=<?php echo $row['user_id']; ?>" 
                                       class="action-btn btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button onclick="confirmDelete('<?php echo $row['user_id']; ?>', '<?php echo addslashes($row['full_name']); ?>')"
                                            class="action-btn btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-records">
                <i class="fas fa-users-slash" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.5;"></i>
                <h3>No students found in the database</h3>
                <p>Click "Add New Student" button to register the first student</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Back to Login Button -->
    <div style="text-align: center; margin-top: 30px;">
        <a href="Login.php" class="go-to-login">
            <i class="fas fa-arrow-left"></i> Back to Student Login
        </a>
    </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(userId, userName) {
    Swal.fire({
        title: 'Delete Student?',
        html: `Are you sure you want to delete <strong>${userName}</strong>?<br>This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        background: '#1a1a1d',
        color: 'white'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'manage_students.php?delete_id=' + userId;
        }
    });
}
</script>

</body>
</html>
<?php $conn->close(); ?>