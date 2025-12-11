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
    <title>เข้าสู่ระบบ - ระบบจัดการสมาชิก</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body
    class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-8 text-center">
                <div class="bg-white rounded-full w-24 h-24 mx-auto flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-lock text-purple-600 text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">เข้าสู่ระบบ</h1>
                <p class="text-blue-100">ระบบจัดการข้อมูลสมาชิก</p>
            </div>

            <!-- Login Form -->
            <form action="check_login.php" method="POST" class="p-8 space-y-6">
                <?php if (isset($_GET['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php
                        if ($_GET['error'] == 'invalid') {
                            echo 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
                        } elseif ($_GET['error'] == 'empty') {
                            echo 'กรุณากรอกข้อมูลให้ครบถ้วน';
                        } elseif ($_GET['error'] == 'inactive') {
                            echo 'บัญชีของคุณถูกระงับการใช้งาน';
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['logout'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                        <i class="fas fa-check-circle mr-2"></i>
                        ออกจากระบบเรียบร้อยแล้ว
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['register'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                        <i class="fas fa-check-circle mr-2"></i>
                        สมัครสมาชิกเรียบร้อยแล้ว กรุณาเข้าสู่ระบบ
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-user mr-2"></i>ชื่อผู้ใช้
                    </label>
                    <input type="text" name="username"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none transition-colors"
                        placeholder="กรุณากรอกชื่อผู้ใช้" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2"></i>รหัสผ่าน
                    </label>
                    <input type="password" name="password"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none transition-colors"
                        placeholder="กรุณากรอกรหัสผ่าน" required>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>เข้าสู่ระบบ
                </button>

                <div class="text-center text-sm text-gray-500 mt-4">
                    <p><i class="fas fa-info-circle mr-1"></i>ทดสอบ: admin / admin123</p>
                </div>

                <div class="text-center text-sm text-gray-600 mt-4 pt-4 border-t">
                    <p>ยังไม่มีบัญชี?
                        <a href="register.php" class="text-purple-600 font-semibold hover:underline">
                            <i class="fas fa-user-plus mr-1"></i>สมัครสมาชิก
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>