<?php
require_once 'config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ตรวจสอบสิทธิ์ admin
$is_admin = ($_SESSION['position'] == 'admin');

// ดึงข้อมูลสมาชิกทั้งหมด
$query = "SELECT * FROM users ORDER BY id ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการสมาชิก</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-white rounded-full p-3 shadow-lg">
                        <i class="fas fa-users text-purple-600 text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">ระบบจัดการข้อมูลสมาชิก</h1>
                        <p class="text-blue-100">ยินดีต้อนรับ, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo $is_admin ? 'Admin' : 'User'; ?>)</p>
                    </div>
                </div>
                <a href="logout.php" class="bg-white text-purple-600 px-6 py-3 rounded-xl font-semibold flex items-center space-x-2 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>ออกจากระบบ</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
            <i class="fas fa-check-circle mr-2"></i>
            <?php 
                if ($_GET['success'] == 'added') echo 'เพิ่มสมาชิกเรียบร้อยแล้ว';
                elseif ($_GET['success'] == 'updated') echo 'แก้ไขข้อมูลเรียบร้อยแล้ว';
                elseif ($_GET['success'] == 'deleted') echo 'ลบสมาชิกเรียบร้อยแล้ว';
                elseif ($_GET['success'] == 'status') echo 'เปลี่ยนสถานะเรียบร้อยแล้ว';
            ?>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white"><i class="fas fa-users mr-2"></i>สมาชิก</h2>
                <?php if ($is_admin): ?>
                <button onclick="openAddModal()" class="bg-white text-purple-600 px-6 py-2 rounded-xl font-semibold flex items-center space-x-2 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-user-plus"></i>
                    <span>เพิ่มสมาชิก</span>
                </button>
                <?php endif; ?>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">ลำดับ</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Username</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Followers</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Position</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">สถานะ</th>
                            <?php if ($is_admin): ?>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">จัดการ</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php 
                        $no = 1;
                        while ($row = $result->fetch_assoc()): 
                        ?>
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-colors">
                            <td class="px-6 py-4 text-gray-700"><?php echo $no++; ?></td>
                            <td class="px-6 py-4 font-medium text-gray-900"><?php echo htmlspecialchars($row['username']); ?></td>
                            <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($row['followers']); ?></td>
                            <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $row['position'] == 'admin' ? 'bg-gradient-to-r from-red-500 to-pink-500 text-white' : 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white'; ?>">
                                    <?php echo $row['position']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($is_admin): ?>
                                <a href="toggle_status.php?id=<?php echo $row['id']; ?>" 
                                   class="inline-block px-4 py-2 rounded-lg text-xs font-semibold text-white transition-all transform hover:scale-105 <?php echo $row['status'] == 'active' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 'bg-gradient-to-r from-red-500 to-pink-500'; ?>">
                                    <?php echo $row['status'] == 'active' ? 'ใช้งาน' : 'ปิดใช้งาน'; ?>
                                </a>
                                <?php else: ?>
                                <span class="px-4 py-2 rounded-lg text-xs font-semibold text-white <?php echo $row['status'] == 'active' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 'bg-gradient-to-r from-red-500 to-pink-500'; ?>">
                                    <?php echo $row['status'] == 'active' ? 'ใช้งาน' : 'ปิดใช้งาน'; ?>
                                </span>
                                <?php endif; ?>
                            </td>
                            <?php if ($is_admin): ?>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="edit_member.php?id=<?php echo $row['id']; ?>" 
                                       class="p-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg hover:shadow-lg transform hover:scale-110 transition-all">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete_member.php?id=<?php echo $row['id']; ?>" 
                                       onclick="return confirm('คุณต้องการลบสมาชิกนี้ใช่หรือไม่?')"
                                       class="p-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:shadow-lg transform hover:scale-110 transition-all">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <?php if ($is_admin): ?>
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                <h3 class="text-2xl font-bold text-white">เพิ่มสมาชิกใหม่</h3>
            </div>
            
            <form action="add_member.php" method="POST" class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Followers</label>
                    <input type="text" name="followers" class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Position</label>
                    <select name="position" class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:outline-none">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                        เพิ่มสมาชิก
                    </button>
                    <button type="button" onclick="closeAddModal()" class="flex-1 bg-gradient-to-r from-gray-400 to-gray-500 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                        ยกเลิก
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }
        
        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>