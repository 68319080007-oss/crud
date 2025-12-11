<?php
// กำหนดค่าการเชื่อมต่อฐานข้อมูล
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'member_system');

// สร้างการเชื่อมต่อ
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// ตั้งค่า charset เป็น UTF-8
$conn->set_charset("utf8mb4");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// เริ่มต้น session
session_start();
?>