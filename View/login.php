<?php
session_start();
require_once __DIR__ . '/../config/database.php'; // Đảm bảo đường dẫn đúng

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra thông tin đăng nhập
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        // Lưu thông tin người dùng vào session
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userName'] = $user['Username'];
        $_SESSION['userRole'] = $user['Role']; // Lưu vai trò người dùng

        // Chuyển hướng đến trang chính hoặc trang dành cho admin nếu có phân quyền
        if ($user['Role'] === 'Admin') {
            header('Location: ../admin/index.php'); // Chuyển đến trang quản trị viên (admin)
            exit();
        } elseif ($user['Role'] === 'User') {
            header('Location: ../index.php'); // Chuyển đến trang người dùng (user)
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Đăng Nhập</h1>

    <?php if (isset($_SESSION['login_error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
    </form>
    <p class="mt-3">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
</div>
</body>
</html>
