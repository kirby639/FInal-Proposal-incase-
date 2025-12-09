<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Connected to MySQL successfully!<br>";
    echo "✅ Server version: " . $conn->server_info;
}
$conn->close();
?>