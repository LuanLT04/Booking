<?php
session_start();
require_once __DIR__ . '/../config/database.php'; // Đảm bảo đường dẫn đúng

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $role = $_POST['role']; // Lấy giá trị từ form

    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Username = ?");
    $stmt->execute([$username]);
    $existingUser  = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser ) {
        $_SESSION['register_error'] = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
    } else {
        // Thêm người dùng vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO Users (Username, Password, FullName, Email, PhoneNumber, Role) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $password, $fullName, $email, $phoneNumber, $role])) {
            $_SESSION['register_success'] = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['register_error'] = "Đăng ký không thành công. Vui lòng thử lại.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Đăng Ký</h1>

    <?php if (isset($_SESSION['register_error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['register_success'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="fullName" class="form-label">Họ và Tên</label>
            <input type="text" class="form-control" id="fullName" name="fullName" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phoneNumber " class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Vai trò</label>
            <select class="form-select" id="role" name="role">
                <option value="User ">Người dùng</option>
                <option value="Admin">Quản trị viên</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Đăng Ký</button>
    </form>
    <p class="mt-3">Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
</div>
</body>
</html>