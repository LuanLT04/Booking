<?php
session_start();
require_once '../config/database.php';
spl_autoload_register(function ($className) {
    require_once "../app/models/$className.php";
});

// Khởi tạo đối tượng Promotion
$promotionModel = new Promotion();

if (isset($_POST['submit'])) {
    $promoCode = $_POST['promoCode'];
    $discount = $_POST['discount'];
    $description = $_POST['description'];
    $expiryDate = $_POST['EndDate'];

    // Tạo đối tượng Promotion và gọi phương thức tạo phiếu giảm giá
    $promotion = new Promotion();
    $isAdded = $promotion->createPromotion($promoCode, $discount, $description, $expiryDate);

    if ($isAdded) {
        $_SESSION['message'] = "Thêm phiếu giảm giá thành công!";
        header('Location: PromotionManagement.php'); // Chuyển hướng sang trang quản lý phiếu giảm giá
        exit();
    } else {
        $_SESSION['message'] = "Lỗi khi thêm phiếu giảm giá!";
        header('Location: PromotionManagement.php');
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Phiếu Giảm Giá</title>
    <link rel="stylesheet" href="../public/Promotion.css">
</head>

<body>
    <h2>Thêm phiếu giảm giá</h2>

    <form method="POST" action="">
        <div class="form-group">
            <label for="promoCode">Mã Phiếu Giảm Giá:</label>
            <input type="text" id="promoCode" name="promoCode" required>
        </div>
        <div class="form-group">
            <label for="discount">Số Tiền Giảm:</label>
            <input type="number" id="discount" name="discount" required>
        </div>
        <div class="form-group">
            <label for="description">Mô Tả:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="EndDate">Ngày Hết Hạn:</label>
            <input type="date" id="EndDate" name="EndDate" required>
        </div>
        <button type="submit" class="submit-btn" name="submit">Thêm Phiếu Giảm Giá</button>
    </form>

</body>

</html>