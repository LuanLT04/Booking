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

// Truy vấn dữ liệu chuyến bay
$flights = $flightModel->getFlights(); // Sử dụng $flightModel ở đây



// Thống kê
$totalFlights = $flightModel->count(); // Dùng $flightModel
$totalUsers = $userModel->count("SELECT COUNT(*) FROM Users");
$totalRevenue = $bookingModel->sum("SELECT SUM(TotalAmount) FROM Bookings WHERE Status = 'Paid'");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị - Booking</title>
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

    <!-- Trang chủ -->
    <div class="home-section">
        <h2>Chào Mừng Đến Với Trang Quản Trị</h2>
        <p>Quản lý chuyến bay, vé, giảm giá và ghế ngồi cho hệ thống của bạn.</p>

        <!-- Thống kê -->
        <div class="statistics">
            <h3>Tổng Số Chuyến Bay</h3>
            <p>Số lượng chuyến bay hiện có: <?= $totalFlights ?></p>

            <!-- Bảng thông tin chuyến bay -->
            <table>
                <thead>
                    <tr>
                        <th>Điểm Khởi Hành</th>
                        <th>Điểm Đến</th>
                        <th>Ngày Khởi Hành</th>
                        <th>Giá Vé</th> <!-- Loại bỏ cột Số Ghế Còn Lại -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Lấy thông tin chuyến bay
                    $flights = $flightModel->select("SELECT * FROM Flights WHERE Status = 'Available'");
                    foreach ($flights as $flight) {
                        echo "<tr>
                                <td>{$flight['Departure']}</td>
                                <td>{$flight['Destination']}</td>
                                <td>{$flight['DepartureDate']}</td>
                                <td>" . number_format($flight['PricePerSeat'], 0, ',', '.') . " VND</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <h3>Tổng Số Người Dùng</h3>
            <div class="user-count">
                <span class="count"><?= $totalUsers ?></span>
                <img src="../public/image/user.png" alt="User Icon" class="user-icon">
            </div>

            <h3>Tổng Doanh Thu</h3>
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>
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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Doanh Thu'],
                datasets: [{
                    label: 'Doanh Thu',
                    data: [<?= $totalRevenue ?>],
                    backgroundColor: '#4CAF50',
                    borderColor: '#4CAF50',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
