<?php
require 'includes/config.php';

 $error = "";
 $info = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean($_POST['email']);
    
    $res = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($res->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); 
        
        $conn->query("UPDATE users SET reset_token='$token', reset_expiry='$expiry' WHERE email='$email'");
        
        $reset_link = "http://localhost/fbu_seminar/reset_password.php?email=$email&token=$token";
        
        $info = "Chúng tôi đã tạo liên kết khôi phục mật khẩu. Click vào link dưới để tiếp tục:<br><br>
                 <a href='$reset_link' style='color:var(--accent); font-weight:bold; word-break:break-all;'>$reset_link</a>";
    } else {
        $error = "Email này không tồn tại trong hệ thống.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0c1015; --bg-secondary: #141a22; --fg: #e8edf4; 
            --fg-muted: #6b7a8f; --accent: #d4a056; --border: #252f3d; 
            --danger: #f87171; --info: #60a5fa;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--fg); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .bg-mesh { position: fixed; inset: 0; z-index: -1; background: radial-gradient(ellipse at 20% 30%, rgba(212, 160, 86, 0.08) 0%, transparent 50%), linear-gradient(180deg, var(--bg) 0%, var(--bg-secondary) 100%); }
        .glass-panel { background: rgba(21, 28, 37, 0.9); backdrop-filter: blur(12px); border: 1px solid var(--border); width: 100%; max-width: 450px; border-radius: 20px; padding: 40px; }
        h1 { font-size: 24px; font-weight: 700; margin-bottom: 5px; }
        .subtitle { color: var(--fg-muted); font-size: 14px; margin-bottom: 25px; }
        .input-group { margin-bottom: 1rem; }
        .input-group label { display: block; font-size: 13px; color: var(--fg-muted); margin-bottom: 6px; }
        .input-group input { width: 100%; padding: 12px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; color: var(--fg); font-size: 14px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 12px 20px; border-radius: 8px; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; font-size: 14px; width: 100%; background: var(--accent); color: #000; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; line-height: 1.5; }
        .alert-danger { background: rgba(248, 113, 113, 0.1); color: var(--danger); }
        .alert-info { background: rgba(96, 165, 250, 0.1); color: var(--info); }
        .footer-link { text-align: center; margin-top: 20px; font-size: 13px; color: var(--fg-muted); }
        .footer-link a { color: var(--accent); text-decoration: none; }
    </style>
</head>
<body>
    <div class="bg-mesh"></div>

    <div class="glass-panel">
        <h1>Quên mật khẩu?</h1>
        <p class="subtitle">Nhập email đăng ký để khôi phục mật khẩu.</p>

        <?php if($error) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
        <?php if($info) echo '<div class="alert alert-info">'.$info.'</div>'; ?>

        <?php if(!$info): ?>
        <form method="POST">
            <div class="input-group">
                <label>Email đăng ký</label>
                <input type="email" name="email" placeholder="email@fbu.edu.vn" required>
            </div>
            <button type="submit" class="btn">Gửi yêu cầu</button>
        </form>
        <?php endif; ?>

        <div class="footer-link">
            <a href="login.php">Quay lại Đăng nhập</a>
        </div>
    </div>
</body>
</html>