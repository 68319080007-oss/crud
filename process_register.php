<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $followers = trim($_POST['followers'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // ตรวจสอบว่ากรอกข้อมูลครบหรือไม่
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        header("Location: register.php?error=empty");
        exit();
    }
    
    // ตรวจสอบรูปแบบอีเมล
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=invalid_email");
        exit();
    }
    
    // ตรวจสอบความยาวของรหัสผ่าน
    if (strlen($password) < 6) {
        header("Location: register.php?error=password_short");
        exit();
    }
    
    // ตรวจสอบรหัสผ่านตรงกันหรือไม่
    if ($password !== $confirm_password) {
        header("Location: register.php?error=password_mismatch");
        exit();
    }
    
    // ตรวจสอบว่าชื่อผู้ใช้ซ้ำหรือไม่
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: register.php?error=username_exists");
        exit();
    }
    $stmt->close();
    
    // ตรวจสอบว่าอีเมลซ้ำหรือไม่
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: register.php?error=email_exists");
        exit();
    }
    $stmt->close();
    
    // เข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // บันทึกข้อมูลลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, followers, position, status) VALUES (?, ?, ?, ?, 'user', 'active')");
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $followers);
    
    if ($stmt->execute()) {
        // สมัครสมาชิกสำเร็จ
        $_SESSION['register_success'] = true;
        header("Location: login.php?register=success");
        exit();
    } else {
        // สมัครสมาชิกล้มเหลว
        header("Location: register.php?error=register_failed");
        exit();
    }
    
    $stmt->close();
} else {
    header("Location: register.php");
    exit();
}

$conn->close();
?>