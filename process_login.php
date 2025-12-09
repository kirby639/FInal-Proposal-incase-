<?php
require "db_connect.php";

$user_id = $_POST['user_id'];
$full_name = $_POST['full_name'];

$sql = "SELECT * FROM students WHERE user_id = '$user_id' AND full_name = '$full_name'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    session_start();
    $_SESSION["loggedInUser"] = $user_id;
    header("Location: Picture.php"); // PHP version of Picture.html
} else {
    echo "<script>alert('Incorrect ID or Name!'); window.location='Login.php';</script>";
}

$conn->close();
?>
