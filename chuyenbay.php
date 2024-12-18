<?php
session_start();

// Kiểm tra trạng thái đăng nhập
$isLoggedIn = isset($_SESSION['isLoggedIn']) ? $_SESSION['isLoggedIn'] : false; // Mặc định là false nếu chưa đăng nhập
$userName = isset($_SESSION['userName']) ? $_SESSION['userName'] : '';

// Kết nối đến cơ sở dữ liệu
require_once 'config/database.php';
spl_autoload_register(function ($className) {
    require_once "app/models/$className.php";
});

// Khởi tạo biến để lưu kết quả chuyến bay
$flights = [];

// Xử lý tìm kiếm chuyến bay
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy giá trị từ form
    $departure = isset($_POST['departure']) ? trim($_POST['departure']) : '';
    $destination = isset($_POST['destination']) ? trim($_POST['destination']) : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';

    // Kiểm tra xem các biến có giá trị hợp lệ không
    if (!empty($departure) && !empty($destination) && !empty($date)) {
        $flightModel = new Flight();
        $flights = $flightModel->select("SELECT * FROM Flights WHERE Departure = ? AND Destination = ? AND DATE(DepartureDate) = ?", [$departure, $destination, $date]);
    } else {
        $error = "Vui lòng điền đầy đủ thông tin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm Chuyến Bay</title>
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

    <!-- Giao diện tìm kiếm chuyến bay -->
    <div class="search-section">
        <h2>Tìm Chuyến Bay</h2>
        <form action="chuyenbay.php" method="POST">
            <label for="departure">Điểm Khởi Hành:</label>
            <input type="text" name="departure" id="departure" required>
            <label for="destination">Điểm Đến:</label>
            <input type="text" name="destination" id="destination" required>
            <label for="date">Ngày Bay:</label>
            <input type="date" name="date" id="date" required>
            <button type="submit">Tìm Chuyến Bay</button>
        </form>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>

    <!-- Kết quả tìm kiếm chuyến bay -->
    <div class="flights-section">
        <h2>Chuyến Bay Tìm Thấy</h2 
        <div class="flights">
            <?php if (empty($flights)): ?>
                <p>Không tìm thấy chuyến bay nào.</p>
            <?php else: ?>
                <?php foreach ($flights as $flight): ?>
                    <div class="flight">
                        <img src="public/image/<?= !empty($flight['Image']) ? htmlspecialchars($flight['Image']) : 'default-image.jpg' ?>"
                             alt="<?= htmlspecialchars($flight['Departure']) ?> - <?= htmlspecialchars($flight['Destination']) ?>">
                        <div class="flight-info">
                            <div class="info">
                                <h3><?= htmlspecialchars($flight['Departure']) ?> - <?= htmlspecialchars($flight['Destination']) ?></h3>
                                <p>Ngày khởi hành: <?= htmlspecialchars($flight['DepartureDate']) ?></p>
                                <p>Giá vé: <?= number_format($flight['PricePerSeat'], 0, ',', '.') ?> VND</p>
                            </div>
                            <a href="book-flight.php?flight_id=<?= $flight['FlightID'] ?>" class="book-button">Đặt Vé</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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