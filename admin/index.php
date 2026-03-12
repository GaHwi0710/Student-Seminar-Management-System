<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}
require '../config.php';
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Admin - Hệ thống Quản lý Hội thảo</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="dashboard">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>Menu Admin</h3>
                </div>
                <ul class="nav">
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="manage_users.php">Quản lý người dùng</a></li>
                    <li><a href="reports.php">Thống kê</a></li>
                    <li><a href="../logout.php">Đăng xuất</a></li>
                </ul>
            </div>
            <div class="content">
                <h1>Chào mừng, <?php echo $name; ?>!</h1>
                <p>Đây là trang quản trị hệ thống.</p>
            </div>
        </div>
    </div>
</body>
</html>