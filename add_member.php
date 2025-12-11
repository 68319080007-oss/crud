<?php
require_once 'config.php';

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['user_id']) || $_SESSION['position'] != 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $followers = $_POST['followers'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $position = $_POST['position'];
    
    $stmt = $conn->prepare("INSERT INTO users (username, password, followers, email, position) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $followers, $email, $position);
    
    if ($stmt->execute()) {
        header("Location: index.php?success=added");
    } else {
        header("Location: index.php?error=add_failed");
    }
    
    $stmt->close();
}

$conn->close();
?>