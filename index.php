<?php
session_start();

// Kiểm tra xem người dùng có đăng nhập hay không
$isLoggedIn = isset($_SESSION['isLoggedIn']) ? $_SESSION['isLoggedIn'] : false;
$userRole = isset($_SESSION['userRole']) ? $_SESSION['userRole'] : '';
$userName = isset($_SESSION['userName']) ? $_SESSION['userName'] : '';

if (!$isLoggedIn) {
    header("Location: View/login.php");
    exit();
}

// Lấy danh sách chuyến bay hiện tại và chuyến bay giảm giá (Ví dụ lấy từ database)
require_once 'config/database.php';
spl_autoload_register(function ($className) {
    require_once "app/models/$className.php";
});

// Lấy thông tin mã giảm giá từ database
$promoModel = new Promotion();
$promotions = $promoModel->select("SELECT * FROM Promotions WHERE Status = 'Active'");

// Lấy thông tin chuyến bay
$flightModel = new Flight();
$flights = $flightModel->select("SELECT * FROM Flights WHERE Status = 'Available'");
?>

<!-- HTML content continues... -->


<!-- HTML content continues... -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Booking</title>
    <link rel="stylesheet" href="public/style.css"> <!-- Bao gồm CSS cho trang -->
    <!-- Thêm link FontAwesome -->
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
                <li><a href="View/logout.php" class="logout">Logout</a></li>
            <?php else: ?>
                <li><a href="View/login.php" class="login">Login</a></li>
            <?php endif; ?>
            <li><a href="account.php" class="account-icon">
                    <img src="public/image/user.png" alt="Account Icon">
                </a></li>
        </ul>
    </nav>


    <!-- Banner -->
    <!-- Banner -->
    <div class="banner-container">
        <button class="prev-button" onclick="changeImage(-1)">&#10094;</button> <!-- Mũi tên trái -->
        <img src="public/image/IMG_9678.JPG" alt="Banner Image" class="banner-image" height="400px">
        <button class="next-button" onclick="changeImage(1)">&#10095;</button> <!-- Mũi tên phải -->
    </div>



    <!-- Giao diện đặt vé -->
    <div class="booking-section">
        <h2>Đặt Vé</h2>
        <form action="booking-process.php" method="POST">
            <label for="departure">Điểm Khởi Hành:</label>
            <input type="text" name="departure" id="departure" required>
            <label for="destination">Điểm Đến:</label>
            <input type="text" name="destination" id="destination" required>
            <label for="date">Ngày Bay:</label>
            <input type="date" name="date" id="date" required>
            <button type="submit">Tìm Chuyến Bay</button>
        </form>
    </div>

    <!-- Chuyến bay hiện tại -->
    <div class="flights-section">
        <h2>Chuyến Bay Hiện Có</h2>
        <div class="flights">
            <?php foreach ($flights as $flight): ?>
                <div class="flight">
                    <!-- Kiểm tra và hiển thị hình ảnh của chuyến bay -->
                    <?php if (!empty($flight['Image'])): ?>
                        <img src="public/image/<?= !empty($flight['Image']) ? htmlspecialchars($flight['Image']) : 'default-image.jpg' ?>"
                            alt="<?= htmlspecialchars($flight['Departure']) ?> - <?= htmlspecialchars($flight['Destination']) ?>">
                    <?php else: ?>
                        <img src="default-image.jpg" alt="No Image Available">
                    <?php endif; ?>

                    <div class="flight-info">
                        <!-- Thông tin chuyến bay bên trái -->
                        <div class="info">
                            <h3><?= htmlspecialchars($flight['Departure']) ?> -
                                <?= htmlspecialchars($flight['Destination']) ?></h3>
                            <p>Ngày khởi hành: <?= htmlspecialchars($flight['DepartureDate']) ?></p>
                            <p>Giá vé: <?= number_format($flight['PricePerSeat'], 0, ',', '.') ?> VND</p>
                        </div>

                        <!-- Nút đặt vé bên phải -->
                        <a href="book-flight.php?flight_id=<?= $flight['FlightID'] ?>" class="book-button">Đặt Vé</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>



    <!-- Phiếu mã giảm giá -->
    <div class="discounted-flights">
        <h2>Phiếu mã giảm giá</h2>
        <div class="coupons">
            <!-- Lặp qua từng mã giảm giá và hiển thị -->
            <?php foreach ($promotions as $promo): ?>
                <div class="coupon">
                    <img src="public/image/coupon1.jpg" alt="Coupon Image"> <!-- Thêm ảnh cho coupon -->
                    <div class="coupon-info">
                        <h3>Giảm giá <?= htmlspecialchars($promo['Discount']) ?>%</h3>
                        <p>Áp dụng cho <?= htmlspecialchars($promo['PromoCode']) ?></p>
                        <div class="coupon-code">
                            <span id="couponCode<?= $promo['PromoID'] ?>">Mã giảm giá:
                                <?= htmlspecialchars($promo['PromoCode']) ?></span>
                            <i class="copy-icon fa fa-clipboard"
                                onclick="copyCouponCode('couponCode<?= $promo['PromoID'] ?>')"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <!-- Thông tin liên lạc -->
            <div class="contact-info">
                <h3>Liên Hệ</h3>
                <ul>
                    <li><i class="fa fa-map-marker-alt"></i> Địa chỉ: 123 Đường ABC, Quận XYZ, Thành phố Hồ Chí Minh
                    </li>
                    <li><i class="fa fa-phone-alt"></i> SĐT: (+84) 123 456 789</li>
                    <li><i class="fa fa-envelope"></i> Email: contact@booking.com</li>
                </ul>
            </div>

            <!-- Các đối tác -->
            <div class="partners">
                <h3>Đối Tác</h3>
                <div class="partner-logos">
                    <!-- Logo các đối tác viễn thông -->
                    <img src="public/image/viettel.jpg" alt="Viettel Logo" class="partner-logo">
                    <img src="public/image/vnpt.png" alt="VNPT Logo" class="partner-logo">
                    <img src="public/image/mb.jpg" alt="Mobifone Logo" class="partner-logo">
                </div>
            </div>

            <!-- Các đối tác thanh toán -->
            <div class="payment-partners">
                <h3>Đối Tác Thanh Toán</h3>
                <div class="payment-logos">
                    <!-- Logo các đối tác thanh toán -->
                    <img src="public/image/vn.jpg" alt="VNPAY Logo" class="payment-logo">
                    <img src="public/image/momo.jpg" alt="Momo Logo" class="payment-logo">
                    <img src="public/image/zl.jpg" alt="ZaloPay Logo" class="payment-logo">
                </div>
            </div>
        </div>
        <p>&copy; 2024 Booking System. All rights reserved.</p>
    </footer>

    <script>
        let currentImageIndex = 0;
        const images = [
            "public/image/IMG_9678.JPG", // Đường dẫn đến ảnh đầu tiên
            "public/image/anh-nen-imac-5k.jpg", // Thêm các ảnh khác
        ];

        // Hàm thay đổi ảnh
        function changeImage(direction) {
            currentImageIndex += direction;

            // Đảm bảo index ảnh nằm trong phạm vi
            if (currentImageIndex < 0) {
                currentImageIndex = images.length - 1; // Quay lại ảnh cuối nếu đến ảnh đầu tiên
            } else if (currentImageIndex >= images.length) {
                currentImageIndex = 0; // Quay lại ảnh đầu nếu đến ảnh cuối
            }

            const bannerImage = document.querySelector('.banner-image');

            // Di chuyển ảnh hiện tại ra ngoài màn hình
            bannerImage.style.left = direction > 0 ? '-100%' : '100%';

            // Đợi khi ảnh đã ra ngoài hoàn toàn (1s)
            setTimeout(() => {
                bannerImage.src = images[currentImageIndex]; // Cập nhật ảnh mới
                bannerImage.style.left = direction > 0 ? '100%' : '-100%'; // Di chuyển ảnh mới vào từ bên ngoài
            }, 1000); // Thời gian trễ bằng với hiệu ứng chuyển động

            // Đợi 1s (thời gian hiệu ứng) và sau đó di chuyển ảnh mới vào vị trí
            setTimeout(() => {
                bannerImage.style.left = '0'; // Đưa ảnh vào giữa
            }, 1100); // Thời gian này phải dài hơn thời gian chuyển ảnh (1000ms)
        }

        // Hàm sao chép mã giảm giá vào clipboard
        function copyCouponCode(couponId) {
            // Lấy mã giảm giá từ phần tử có ID tương ứng
            var couponCode = document.getElementById(couponId).textContent;

            // Tạo một textarea tạm thời để sao chép mã giảm giá
            var tempTextArea = document.createElement("textarea");
            tempTextArea.value = couponCode;
            document.body.appendChild(tempTextArea);

            // Chọn và sao chép nội dung
            tempTextArea.select();
            document.execCommand("copy");

            // Xóa textarea sau khi sao chép
            document.body.removeChild(tempTextArea);

            // Thông báo đã sao chép
            alert("Mã giảm giá " + couponCode + " đã được sao chép!");
        }
    </script>
</body>


</html>