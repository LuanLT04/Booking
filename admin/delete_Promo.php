<?php
session_start();
require_once '../config/database.php';
spl_autoload_register(function ($className) {
    require_once "../app/models/$className.php";
});

// Xử lý khi người dùng yêu cầu xóa
if (isset($_GET['id'])) {
    $promoId = $_GET['id'];
    $promotionModel = new Promotion();
    $promotionModel->deletePromotion($promoId);
    
    // Chuyển hướng sau khi xóa thành công
    header("Location: PromotionManagement.php");  // Đảm bảo đường dẫn đúng
    exit();  // Dừng ngay lập tức
} else {
    // Xử lý nếu không có ID được truyền vào
    echo "ID mã giảm giá không hợp lệ!";
}
?>
