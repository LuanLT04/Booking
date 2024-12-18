<?php
session_start();
// Tải các model
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

// Khởi tạo các model
$flightModel = new Flight();
$userModel = new User();
$bookingModel = new Booking();

// Kiểm tra hành động (Duyệt hoặc Xóa)
if (isset($_GET['action']) && isset($_GET['flight_id'])) {
    $flightId = $_GET['flight_id'];
    if ($_GET['action'] == 'approve') {
        // Duyệt chuyến bay, thay đổi trạng thái chuyến bay thành 'Available'
        $flightModel->updateStatus($flightId, 'Available');
    } elseif ($_GET['action'] == 'delete') {
        // Xóa chuyến bay
        $flightModel->deleteFlight($flightId);
    }
}

// Truy vấn danh sách chuyến bay
$flights = $flightModel->getFlights(); // Sử dụng phương thức getFlights() để lấy danh sách chuyến bay

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Chuyến Bay</title>
    <link rel="stylesheet" href="../public/indexAdmin.css"> <!-- Bao gồm CSS cho trang -->
</head>

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

    <!-- Quản lý chuyến bay -->
    <div class="home-section">
        <h2>Quản Lý Chuyến Bay</h2>

        <!-- Bảng thông tin chuyến bay -->
        <table>
            <thead>
                <tr>
                    <th>Điểm Khởi Hành</th>
                    <th>Điểm Đến</th>
                    <th>Ngày Khởi Hành</th>
                    <th>Giá Vé</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Lấy thông tin chuyến bay
                foreach ($flights as $flight) {
                    echo "<tr>
                            <td>{$flight['Departure']}</td>
                            <td>{$flight['Destination']}</td>
                            <td>{$flight['DepartureDate']}</td>
                            <td>" . number_format($flight['PricePerSeat'], 0, ',', '.') . " VND</td>
                            <td>{$flight['Status']}</td>
                            <td>
                               <a href='?action=approve&flight_id=<?= {$flight['FlightID']} ?>' class='approve-btn'>Duyệt</a>
                                <a href='?action=delete&flight_id={$flight['FlightID']}' class='delete-btn' onclick='return confirm(\"Bạn có chắc chắn muốn xóa chuyến bay này?\")'>Xóa</a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <!-- Thông tin liên lạc -->
            <div class="contact-info">
                <h3>Liên Hệ</h3>
                <ul>
                    <li><i class="fa fa-map-marker-alt"></i> Địa chỉ: 123 Đường ABC, Quận XYZ, Thành phố Hồ Chí Minh</li>
                    <li><i class="fa fa-phone-alt"></i> SĐT: (+84) 123 456 789</li>
                    <li><i class="fa fa-envelope"></i> Email: contact@booking.com</li>
                </ul>
            </div>

            <!-- Các đối tác -->
            <div class="partners">
                <h3>Đối Tác</h3>
                <div class="partner-logos">
                    <!-- Logo các đối tác viễn thông -->
                    <img src="../public/image/viettel.jpg" alt="Viettel Logo" class="partner-logo">
                    <img src="../public/image/vnpt.png" alt="VNPT Logo" class="partner-logo">
                    <img src="../public/image/mb.jpg" alt="Mobifone Logo" class="partner-logo">
                </div>
            </div>

            <!-- Các đối tác thanh toán -->
            <div class="payment-partners">
                <h3>Đối Tác Thanh Toán</h3>
                <div class="payment-logos">
                    <!-- Logo các đối tác thanh toán -->
                    <img src="../public/image/vn.jpg" alt="VNPAY Logo" class="payment-logo">
                    <img src="../public/image/momo.jpg" alt="Momo Logo" class="payment-logo">
                    <img src="../public/image/zl.jpg" alt="ZaloPay Logo" class="payment-logo">
                </div>
            </div>
        </div>
        <p>&copy; 2024 Booking System. All rights reserved.</p>
    </footer>
</body>

</html>