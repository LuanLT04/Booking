<?php
session_start();

// Kiểm tra người dùng đã đăng nhập hay chưa
$isLoggedIn = isset($_SESSION['isLoggedIn']) ? $_SESSION['isLoggedIn'] : false;
if (!$isLoggedIn) {
    header("Location: /View/login.php");
    exit();
}

// Lấy thông tin từ form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_id = $_POST['flight_id'];
    $seats_booked = $_POST['seats_booked'];
    $payment_status = $_POST['payment_status'];

    // Kiểm tra và tính toán tổng giá nếu cần
    $total_price = $_POST['total_price'] ?? null; // Nếu có giá trị, lấy từ form, nếu không sẽ tính toán lại sau

    if (!$total_price || !is_numeric($total_price)) {
        // Tính lại tổng giá nếu không có giá trị total_price trong form
        require_once 'config/database.php';
        spl_autoload_register(function ($className) {
            require_once "app/models/$className.php";
        });

        $flightModel = new Flight();
        $flight = $flightModel->select("SELECT * FROM Flights WHERE FlightID = ?", [$flight_id]);

        if ($flight) {
            $price_per_seat = $flight[0]['PricePerSeat']; // Giá vé mỗi ghế
            $total_price = $seats_booked * $price_per_seat; // Tính tổng giá
        }
    }

    // Lấy thông tin người dùng từ session
    $user_id = $_SESSION['userID'] ?? null; // Kiểm tra nếu userID tồn tại trong session

    // Kiểm tra nếu userID là null
    if ($user_id === null) {
        echo "Người dùng chưa đăng nhập!";
        exit();
    }

    // Lưu vào cơ sở dữ liệu
    require_once 'config/database.php';
    spl_autoload_register(function ($className) {
        require_once "app/models/$className.php";
    });

    // Tạo một instance của lớp Booking để xử lý đặt vé
    $bookingModel = new Booking();

    // Thực hiện đặt vé
    $bookingData = [
        'user_id' => $user_id,
        'flight_id' => $flight_id,
        'seats_booked' => $seats_booked,
        'total_price' => $total_price,
        'payment_status' => $payment_status,
    ];

    $createBooking = $bookingModel->createBooking($bookingData);

    if ($createBooking) {
        // Đặt vé thành công, chuyển hướng người dùng đến trang xác nhận hoặc trang lịch sử đặt vé
        header("Location: bookings.php?status=success");
        exit();
    } else {
        // Thất bại trong việc đặt vé
        header("Location: book-flight.php?flight_id=$flight_id&status=error");
        exit();
    }
}
?>
