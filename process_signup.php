<?php
require "db_connect.php";

$user_id = $_POST['user_id'];
$full_name = $_POST['full_name'];
$course = $_POST['course'];
$birthdate = $_POST['birthdate'];

$photo = addslashes(file_get_contents($_FILES["photo"]["tmp_name"]));

$sql = "INSERT INTO students (user_id, full_name, course, birthdate, photo)
        VALUES ('$user_id', '$full_name', '$course', '$birthdate', '$photo')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Registration successful!'); window.location='Login.php';</script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
