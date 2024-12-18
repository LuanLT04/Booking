<?php
session_start();
require_once '../config/database.php';
spl_autoload_register(function ($className) {
    require_once "../app/models/$className.php";
});

if (isset($_GET['id'])) {
    $promoID = $_GET['id'];
    // Khởi tạo đối tượng Promotion
    $promotionModel = new Promotion();

    // Lấy thông tin phiếu giảm giá từ cơ sở dữ liệu
    $promoDetails = $promotionModel->getPromotionById($promoID);
}


// Kiểm tra nếu có thông tin gửi lên từ form để cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $promoCode = $_POST['promoCode'];
    $discount = $_POST['discount'];
    $description = $_POST['description'];
    $expiryDate = $_POST['expiry_date'];

    // Cập nhật phiếu giảm giá
    $promotionModel->updatePromotion($promoID, $promoCode, $discount, $description, $expiryDate);
    // Chuyển hướng về trang quản lý sau khi lưu
    header("Location: PromotionManagement.php");
    exit;
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
    <form method="POST" action="">
        <div class="form-group">
            <label for="promoCode">Mã Phiếu Giảm Giá:</label>
            <input type="text" id="promoCode" name="promoCode" value="<?= htmlspecialchars($promoDetails['PromoCode'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="discount">Số Tiền Giảm:</label>
            <input type="number" id="discount" name="discount" value="<?= htmlspecialchars($promoDetails['Discount'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Mô Tả:</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($promoDetails['Description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <input type="date" id="EndDate" name="expiry_date"
                value="<?= isset($promoDetails['EndDate']) ? substr($promoDetails['EndDate'], 0, 10) : '' ?>" required>

        </div>
        <button type="submit" class="submit-btn" name="submit">Cập Nhật Phiếu Giảm Giá</button>
    </form>


</body>

</html>