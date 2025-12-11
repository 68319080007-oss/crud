<?php
require_once 'config.php';

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['user_id']) || $_SESSION['position'] != 'admin') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

// ดึงข้อมูลสมาชิก
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

if (!$member) {
    header("Location: index.php");
    exit();
}

// อัพเดทข้อมูล
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $followers = $_POST['followers'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    
    // ถ้ามีการเปลี่ยนรหัสผ่าน
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username=?, password=?, followers=?, email=?, position=? WHERE id=?");
        $stmt->bind_param("sssssi", $username, $password, $followers, $email, $position, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, followers=?, email=?, position=? WHERE id=?");
        $stmt->bind_param("ssssi", $username, $followers, $email, $position, $id);
    }
    
    if ($stmt->execute()) {
        header("Location: index.php?success=updated");
    } else {
        header("Location: index.php?error=update_failed");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสมาชิก</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen p-4">
    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                <h3 class="text-2xl font-bold text-white">แก้ไขข้อมูลสมาชิก</h3>
            </div>
            
            <form method="POST" class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($member['username']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>