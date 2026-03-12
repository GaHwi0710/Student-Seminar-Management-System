<?php
require 'includes/config.php';

 $error = "";
 $success_msg = "";
 $valid_token = false;

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = clean($_GET['email']);
    $token = clean($_GET['token']);
    
    $sql = "SELECT id FROM users WHERE email='$email' AND reset_token='$token' AND reset_expiry > NOW()";
    $res = $conn->query($sql);
    
    if ($res->num_rows == 1) {
        $valid_token = true;
        $user_data = $res->fetch_assoc();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_pass = $_POST['password'];
            $confirm_pass = $_POST['confirm_password'];
            
            if (strlen($new_pass) < 6) {
                $error = "Mật khẩu phải trên 6 ký tự.";
            } elseif ($new_pass != $confirm_pass) {
                $error = "Mật khẩu xác nhận không khớp.";
            } else {
                $hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $conn->query("UPDATE users SET password='$hash', reset_token=NULL, reset_expiry=NULL WHERE email='$email'");
                $success_msg = "Đổi mật khẩu thành công! Bạn có thể đăng nhập.";
                $valid_token = false; 
            }
        }
    } else {
        $error = "Liên kết không hợp lệ hoặc đã hết hạn.";
    }
} else {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #0c1015; --bg-secondary: #141a22; --fg: #e8edf4; --fg-muted: #6b7a8f; --accent: #d4a056; --border: #252f3d; --success: #34d399; --danger: #f87171; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--fg); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .bg-mesh { position: fixed; inset: 0; z-index: -1; background: radial-gradient(ellipse at 20% 30%, rgba(212, 160, 86, 0.08) 0%, transparent 50%), linear-gradient(180deg, var(--bg) 0%, var(--bg-secondary) 100%); }
        .glass-panel { background: rgba(21, 28, 37, 0.9); backdrop-filter: blur(12px); border: 1px solid var(--border); width: 100%; max-width: 450px; border-radius: 20px; padding: 40px; }
        h1 { font-size: 24px; margin-bottom: 20px; }
        .input-group { margin-bottom: 1rem; }
        .input-group label { display: block; font-size: 13px; color: var(--fg-muted); margin-bottom: 6px; }
        .input-group input { width: 100%; padding: 12px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; color: var(--fg); }
        .btn { width: 100%; padding: 12px; border-radius: 8px; background: var(--accent); color: #000; border: none; cursor: pointer; font-weight: 600; }
        .alert { padding: 10px; border-radius: 8px; margin-bottom: 15px; }
        .alert-danger { background: rgba(248, 113, 113, 0.1); color: var(--danger); }
        .alert-success { background: rgba(52, 211, 153, 0.1); color: var(--success); }
        .footer-link { text-align: center; margin-top: 20px; font-size: 13px; }
        .footer-link a { color: var(--accent); text-decoration: none; }
    </style>
</head>
<body>
    <div class="bg-mesh"></div>
    <div class="glass-panel">
        <h1>Đặt lại mật khẩu</h1>
        
        <?php if($error) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
        <?php if($success_msg) echo '<div class="alert alert-success">'.$success_msg.'</div>'; ?>

        <?php if($valid_token): ?>
        <form method="POST">
            <div class="input-group">
                <label>Mật khẩu mới</label>
                <input type="password" name="password" required>
            </div>
            <div class="input-group">
                <label>Xác nhận mật khẩu mới</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Cập nhật</button>
        </form>
        <?php endif; ?>

        <div class="footer-link">
            <a href="login.php">Về trang đăng nhập</a>
        </div>
    </div>
</body>
</html>