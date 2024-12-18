<?php
session_start();
require_once '../config/database.php';
spl_autoload_register(function ($className) {
    require_once "../app/models/$className.php";
});


// Kiểm tra xem người dùng có đăng nhập hay không
$isLoggedIn = isset($_SESSION['isLoggedIn']) ? $_SESSION['isLoggedIn'] : false;
$userRole = isset($_SESSION['userRole']) ? $_SESSION['userRole'] : '';
$userName = isset($_SESSION['userName']) ? $_SESSION['userName'] : '';

if (!$isLoggedIn) {
    header("Location: View/login.php");
    exit();
}

// Khởi tạo đối tượng Promotion
$promotionModel = new Promotion();

$userModel = new User();

// Lấy tất cả các phiếu giảm giá
$promotions = $promotionModel->getAllPromotions();

// Hiển thị thông báo nếu có
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-success'>{$_SESSION['message']}</div>";
    unset($_SESSION['message']); // Xóa thông báo sau khi đã hiển thị
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Phiếu Giảm Giá</title>
    <link rel="stylesheet" href="../public/indexAdmin.css"> <!-- Bao gồm CSS cho trang -->
    <link rel="stylesheet" href="../public/Promotion.css">
</head>
<style>
    /* CSS cho nút */
    .btn {
        background-color: #45a049;
        /* Màu cam, bạn có thể thay bằng màu khác */
        color: #fff;
        /* Màu chữ trắng */
        padding: 10px 20px;
        /* Kích thước đệm cho nút */
        border: none;
        /* Xóa viền */
        border-radius: 5px;
        /* Bo tròn góc */
        text-decoration: none;
        /* Xóa gạch chân */
        font-size: 16px;
        /* Cỡ chữ */
        cursor: pointer;
        /* Con trỏ chuột */
        display: inline-block;
        /* Đảm bảo định dạng giống nút */
    }

    .btn:hover {
        background-color: #45a049;
        /* Màu nền khi di chuột */
    }
</style>
<body>
    <!-- Thanh điều hướng -->
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="FlightManagement.php">Quản Lý Chuyến Bay</a></li>
            <li><a href="PromotionManagement.php">Phiếu Giảm Giá</a></li>
            <li><a href="themchuyenbay.php">Thêm Chuyến Bay</a></li>
            <li><a href="quanlyghe.php">Quản Lý Ghế</a></li>
        </ul>
        <ul class="user-info">
            <?php if ($isLoggedIn): ?>
                <li><span>Xin chào, <?= htmlspecialchars($userName) ?></span></li>
                <li><a href="../View/logout.php" class="logout">Logout</a></li>
            <?php else: ?>
                <li><a href="../View/login.php" class="login">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    
    <!-- Quản lý phiếu giảm giá -->
    <div class="home-section">
        <div class="add-promo-btn">
            <a href="add_Promo.php" class="btn">Thêm Phiếu Giảm Giá</a>
        </div><br>
        <div class="discount-list">
            <?php
            // Đảo ngược mảng phiếu giảm giá để hiển thị phiếu mới lên đầu
            $promotions = array_reverse($promotions);
            foreach ($promotions as $promo): ?>
                <div class="discount-card">
                    <div class="discount-info">
                        <i class="fa fa-tag"></i>
                        <div class="discount-details">
                            <h4><?= htmlspecialchars($promo['PromoCode']) ?></h4>
                            <p>Giảm: <?= number_format($promo['Discount'], 0, ',', '.') ?> VND</p>
                            <p>Mô tả: <?= isset($promo['Description']) ? htmlspecialchars($promo['Description']) : 'Không có mô tả' ?></p>
                            <p>Hết hạn: <?= isset($promo['EndDate']) ? htmlspecialchars($promo['EndDate']) : 'Chưa xác định' ?></p>
                        </div>
                    </div>
                    <div class="discount-actions">
                        <a href="edit_Promo.php?id=<?= $promo['PromoID'] ?>" class="edit-btn">Sửa</a>
                        <a href="delete_Promo.php?id=<?= $promo['PromoID'] ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu giảm giá này?')">Xóa</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="contact-info">
                <h3>Liên Hệ</h3>
                <ul>
                    <li><i class="fa fa-map-marker-alt"></i> Địa chỉ: 123 Đường ABC, Quận XYZ, Thành phố Hồ Chí Minh</li>
                    <li><i class="fa fa-phone-alt"></i> SĐT: (+84) 123 456 789</li>
                    <li><i class="fa fa-envelope"></i> Email: contact@booking.com</li>
                </ul>
            </div>

            <div class="partners">
                <h3>Đối Tác</h3>
                <div class="partner-logos">
                    <img src="public/image/viettel.jpg" alt="Viettel Logo" class="partner-logo">
                    <img src="public/image/vnpt.png" alt="VNPT Logo" class="partner-logo">
                    <img src="public/image/mb.jpg" alt="Mobifone Logo" class="partner-logo">
                </div>
            </div>

            <div class="payment-partners">
                <h3>Đối Tác Thanh Toán</h3>
                <div class="payment-logos">
                    <img src="public/image/vn.jpg" alt="VNPAY Logo" class="payment-logo">
                    <img src="public/image/momo.jpg" alt="Momo Logo" class="payment-logo">
                    <img src="public/image/zl.jpg" alt="ZaloPay Logo" class="payment-logo">
                </div>
            </div>
        </div>
        <p>&copy; 2024 Booking System. All rights reserved.</p>
    </footer>
</body>

</html>