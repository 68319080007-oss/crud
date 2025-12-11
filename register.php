<?php
session_start();

// ถ้าล็อกอินแล้วให้ไปหน้า index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - ระบบจัดการสมาชิก</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-8 text-center">
                <div class="bg-white rounded-full w-24 h-24 mx-auto flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-user-plus text-purple-600 text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">สมัครสมาชิก</h1>
                <p class="text-blue-100">กรอกข้อมูลเพื่อสร้างบัญชีใหม่</p>
            </div>
            
            <!-- Register Form -->
            <form action="process_register.php" method="POST" class="p-8 space-y-6" id="registerForm">
                <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php 
                        if ($_GET['error'] == 'empty') {
                            echo 'กรุณากรอกข้อมูลให้ครบถ้วน';
                        } elseif ($_GET['error'] == 'password_mismatch') {
                            echo 'รหัสผ่านไม่ตรงกัน';
                        } elseif ($_GET['error'] == 'username_exists') {
                            echo 'ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว';
                        } elseif ($_GET['error'] == 'email_exists') {
                            echo 'อีเมลนี้มีอยู่ในระบบแล้ว';
                        } elseif ($_GET['error'] == 'invalid_email') {
                            echo 'รูปแบบอีเมลไม่ถูกต้อง';
                        } elseif ($_GET['error'] == 'password_short') {
                            echo 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร';
                        } elseif ($_GET['error'] == 'register_failed') {
                            echo 'การสมัครสมาชิกล้มเหลว กรุณาลองใหม่อีกครั้ง';
                        }
                    ?>
                </div>
                <?php endif; ?>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-user mr-2"></i>ชื่อผู้ใช้ <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none transition-colors"
                        placeholder="กรุณากรอกชื่อผู้ใช้"
                        required
                        minlength="3"
                    >
                    <small class="text-gray-500">อย่างน้อย 3 ตัวอักษร</small>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope mr-2"></i>อีเมล <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none transition-colors"
                        placeholder="example@email.com"
                        required
                    >
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-users mr-2"></i>Followers / ข้อมูลเพิ่มเติม
                    </label>
                    <input 
                        type="text" 
                        name="followers" 
                        id="followers"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none transition-colors"
                        placeholder="เช่น: นักศึกษา, พนักงาน"
                    >
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2"></i>รหัสผ่าน <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none transition-colors"
                            placeholder="กรุณากรอกรหัสผ่าน"
                            required
                            minlength="6"
                        >
                        <button
                            type="button"
                            onclick="togglePassword('password')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-purple-600 transition-colors"
                        >
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <small class="text-gray-500">อย่างน้อย 6 ตัวอักษร</small>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2"></i>ยืนยันรหัสผ่าน <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="confirm_password" 
                            id="confirm_password"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none transition-colors"
                            placeholder="กรุณายืนยันรหัสผ่าน"
                            required
                            minlength="6"
                        >
                        <button
                            type="button"
                            onclick="togglePassword('confirm_password')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-purple-600 transition-colors"
                        >
                            <i class="fas fa-eye" id="confirm_password-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        required
                        class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                    >
                    <label for="terms" class="ml-2 text-sm text-gray-700">
                        ฉันยอมรับ <a href="#" class="text-purple-600 hover:underline">ข้อกำหนดและเงื่อนไข</a>
                    </label>
                </div>
                
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                >
                    <i class="fas fa-user-plus mr-2"></i>สมัครสมาชิก
                </button>
                
                <div class="text-center text-sm text-gray-600 mt-4 pt-4 border-t">
                    <p>มีบัญชีอยู่แล้ว? 
                        <a href="login.php" class="text-purple-600 font-semibold hover:underline">
                            <i class="fas fa-sign-in-alt mr-1"></i>เข้าสู่ระบบ
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // ตรวจสอบรหัสผ่านตรงกันหรือไม่
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('รหัสผ่านไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง');
                return false;
            }
        });
    </script>
</body>
</html>