<?php
// Lấy thông tin chuyến bay từ cơ sở dữ liệu (giống như trước)
if (isset($_GET['flight_id'])) {
    $flight_id = $_GET['flight_id'];
    
    // Lấy thông tin chuyến bay
    require_once 'config/database.php';
    spl_autoload_register(function ($className) {
        require_once "app/models/$className.php";
    });

    $flightModel = new Flight();
    $flight = $flightModel->select("SELECT * FROM Flights WHERE FlightID = ?", [$flight_id]);

    if (!$flight) {
        echo "Chuyến bay không tồn tại!";
        exit();
    }
    $flight = $flight[0]; // Chuyến bay duy nhất

    // Tính toán tổng giá (nếu có)
    $price_per_seat = $flight['PricePerSeat'];
    $total_price = $price_per_seat;  // Nếu đặt 1 ghế, giá sẽ là giá của 1 ghế
} else {
    echo "Không có chuyến bay được chọn!";
    exit();
}
?>

<!-- Đặt vé -->
<div class="booking-section">
    <h2>Đặt Vé - <?= htmlspecialchars($flight['Departure']) ?> - <?= htmlspecialchars($flight['Destination']) ?></h2>
    <form action="booking-process.php" method="POST">
        <input type="hidden" name="flight_id" value="<?= $flight['FlightID'] ?>">

        <label for="seats_booked">Số Ghế:</label>
        <input type="number" name="seats_booked" id="seats_booked" required min="1" value="1">

        <label for="total_price">Tổng Giá:</label>
        <input type="text" name="total_price" id="total_price" value="<?= number_format($total_price, 0, ',', '.') ?> VND" disabled>

        <label for="payment_status">Trạng Thái Thanh Toán:</label>
        <select name="payment_status" id="payment_status" required>
            <option value="Pending">Đang Chờ</option>
            <option value="Paid">Đã Thanh Toán</option>
        </select>

        <button type="submit">Đặt Vé</button>
    </form>
</div>
