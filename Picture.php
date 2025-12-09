<?php
session_start();
if (!isset($_SESSION["loggedInUser"])) {
    header("Location: Login.html");
    exit();
}

$userID = $_SESSION["loggedInUser"];

require "db_connect.php";

// Fetch user info from database
$sql = "SELECT * FROM students WHERE user_id = '$userID'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    die("User not found in database.");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CTU ID Card</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .container {
            width: 700px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 0 25px rgba(217, 4, 41, 0.25);
        }

        h1 {
            margin-bottom: 25px;
            font-size: 28px;
            color: #f0eaea;
            text-shadow: 0px 0px 10px rgba(217, 4, 41, 0.7);
        }

        .id-card-container {
            position: relative;
            width: 600px;
            margin: 0 auto;
            color: black;
        }

        #id-template {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        #user-photo-overlay {
            position: absolute;
            width: 233px;
            height: 230px;
            top: 148px;
            left: 190px;
            overflow: hidden;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        #user-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-overlay {
            position: absolute;
            font-family: Arial, sans-serif;
            color: #fcfcfc;
            font-weight: bold;
            width: 100%;
            text-align: center;
        }

        #name-overlay {
            top: 64%;
            left: 0;
            font-size: 21px;
        }

        #course-overlay {
            top: 68.5%;
            left: 0;
            font-size: 21px;
        }

        #id-overlay {
            top: 76.5%;
            left: 0;
            font-size: 20px;
        }

        #logoutBtn {
            margin-top: 30px;
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
        }
        
        #logoutBtn:hover {
            background: #ff1e3c;
            transform: translateY(-3px);
            box-shadow: 0 0 15px rgba(217, 4, 41, 0.8);
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: white;
        }
        
        .btn-manage {
            background: #44151b;
            box-shadow: 0 0 10px rgba(191, 40, 40, 0.556);
        }
        
        .btn-manage:hover {
            background: #080506;
            transform: translateY(-3px);
            box-shadow: 0 0 15px rgba(191, 40, 40, 0.8);
        }
    </style>
</head>

<body>

<div class="container">
    <h1>Cebu Technological University - ID Card</h1>

    <div class="id-card-container">
        <img id="id-template" src="ID.png">

        <!-- PHOTO -->
        <div id="user-photo-overlay">
            <img id="user-pic" src="data:image/jpeg;base64,<?php echo base64_encode($user['photo']); ?>">
        </div>

        <!-- TEXT FIELDS -->
        <div class="info-overlay" id="name-overlay">
            <?php echo htmlspecialchars($user['full_name']); ?>
        </div>

        <div class="info-overlay" id="course-overlay">
            <?php echo htmlspecialchars($user['course']); ?>
        </div>

        <div class="info-overlay" id="id-overlay">
            <?php echo htmlspecialchars($user['user_id']); ?>
        </div>
    </div>
    
    <div class="action-buttons">
        <button id="logoutBtn" onclick="window.location='logout.php'">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>
</div>

</body>
</html>