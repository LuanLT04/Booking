<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng từ session
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Kết nối đến cơ sở dữ liệu
require_once __DIR__ . '/../config/database.php';

// Lấy thông tin người dùng từ cơ sở dữ liệu (nếu cần)
$stmt = $pdo->prepare("SELECT * FROM Users WHERE UserID = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Dashboard</h1>

    <div class="mb-4">
        <h2>Xin chào, <?php echo htmlspecialchars($user['FullName']); ?>!</h2>
        <p>Vai trò của bạn: <strong><?php echo htmlspecialchars($role); ?></strong></p>
    </div>

    <?php if ($role == 'Admin'): ?>
        <h3>Quản lý hệ thống</h3>
        <p>Những chức năng dành cho quản trị viên có thể được thêm vào đây.</p>
        <ul>
            <li><a href="manage_users.php">Quản lý người dùng</a></li>
            <li><a href="manage_bookings.php">Quản lý đặt vé</a></li>
            <li><a href="view_reports.php">Xem báo cáo</a></li>
        </ul>
    <?php else: ?>
        <h3>Thông tin cá nhân</h3>
        <p>Thông tin cá nhân của bạn có thể được hiển thị ở đây.</p>
        <ul>
            <li><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['Username']); ?></li>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($user[' Email']); ?></li>
            <li><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($user['PhoneNumber']); ?></li>
        </ul>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>