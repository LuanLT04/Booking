<?php
session_start();

// Kiểm tra trạng thái đăng nhập
$isLoggedIn = isset($_SESSION['isLoggedIn']) ? $_SESSION['isLoggedIn'] : false; // Mặc định là false nếu chưa đăng nhập
$userName = isset($_SESSION['userName']) ? $_SESSION['userName'] : '';

// Kiểm tra nếu người dùng đã đăng nhập và có userId
if ($isLoggedIn && isset($_SESSION['UserID'])) {
    $userId = $_SESSION['UserID']; // Lấy userId từ session

    // Debugging: Kiểm tra giá trị của userId
    if (empty($userId)) {
        echo "Lỗi: User ID bị trống.";
        exit;
    }

    $bookingModel = new Booking();
    
    // Sử dụng userId trong câu truy vấn
    $bookings = $bookingModel->select("SELECT b.*, f.Departure, f.Destination, f.DepartureDate, f.PricePerSeat 
                                        FROM bookings b 
                                        JOIN flights f ON b.FlightID = f.FlightID 
                                        WHERE b.UserID = ?", [$userId]);
} else {
    $bookings = []; // Nếu người dùng chưa đăng nhập hoặc không có userId trong session
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vé Bay - Booking</title>
    <link rel="stylesheet" href="public/style.css"> <!-- Bao gồm CSS cho trang -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Thanh điều hướng -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="chuyenbay.php">Chuyến Bay</a></li>
            <li><a href="bookings.php">Vé Bay</a></li>
            <li><a href="airlines.php">Xem Chi Tiết Hãng Bay</a></li>
        </ul>
        <ul class="user-info">
            <?php if ($isLoggedIn): ?>
                <li><span>Hello, <?= htmlspecialchars($userName) ?></span></li>
                <li><a href="admin/logout.php" class="logout">Logout</a></li>
            <?php else: ?>
                <li><a href="admin/login.php" class="login">Login</a></li>
            <?php endif; ?>
            <li><a href="account.php" class="account-icon">
                <img src="public/image/user.png" alt="Account Icon">
            </a></li>
        </ul>
    </nav>

    <!-- Giao diện xem vé đã đặt -->
    <div class="bookings-section">
        <h2>Vé Bay Của Bạn</h2>
        <?php if ($isLoggedIn): ?>
            <?php if (empty($bookings)): ?>
                <p>Bạn chưa đặt vé nào.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Điểm Khởi Hành</th>
                            <th>Điểm Đến</th>
                            <th>Ngày Khởi Hành</th>
                            <th>Số Ghế Đặt</th>
                            <th>Tổng Giá</th>
                            <th>Trạng Thái Thanh Toán</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['Departure']) ?></td>
                                <td><?= htmlspecialchars($booking['Destination']) ?></td>
                                <td><?= htmlspecialchars($booking['DepartureDate']) ?></td>
                                <td><?= htmlspecialchars($booking['SeatsBooked']) ?></td>
                                <td><?= number_format($booking['TotalPrice'], 0, ',', '.') ?> VND</td>
                                <td><?= htmlspecialchars($booking['PaymentStatus']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php else: ?>
            <p>Vui lòng <a href="admin/login.php">đăng nhập</a> để xem vé đã đặt của bạn.</p>
        <?php endif; ?>
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
