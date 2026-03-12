<?php
require 'includes/config.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

 $error = "";
 $success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $student_code = clean($_POST['student_code']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($password != $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp!";
    } elseif (strlen($password) < 6) {
        $error = "Mật khẩu phải ít nhất 6 ký tự!";
    } else {
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            $error = "Email này đã được đăng ký!";
        } else {
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (name, email, password, role, student_code) 
                    VALUES ('$name', '$email', '$hash_pass', 'student', '$student_code')";
            
            if ($conn->query($sql)) {
                $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.";
            } else {
                $error = "Có lỗi xảy ra: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FBU Seminar - Đăng ký</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0c1015; --bg-secondary: #141a22; --fg: #e8edf4; 
            --fg-muted: #6b7a8f; --accent: #d4a056; --accent-hover: #e5b367;
            --card: #151c25; --border: #252f3d; --success: #34d399; 
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
        .input-group input:focus { outline: none; border-color: var(--accent); }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 20px; border-radius: 8px; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; font-size: 14px; width: 100%; }
        .btn-primary { background: var(--accent); color: #000; }
        .btn-primary:hover { background: var(--accent-hover); }
        .alert { padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; }
        .alert-danger { background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.3); color: var(--danger); }
        .alert-success { background: rgba(52, 211, 153, 0.1); border: 1px solid rgba(52, 211, 153, 0.3); color: var(--success); }
        .footer-link { text-align: center; margin-top: 20px; font-size: 13px; color: var(--fg-muted); }
        .footer-link a { color: var(--accent); text-decoration: none; font-weight: 500; }
    </style>
</head>
<body>
    <div class="bg-mesh"></div>

    <div class="glass-panel">
        <h1>Đăng ký tài khoản</h1>
        <p class="subtitle">Tham gia hệ thống FBU Seminar ngay hôm nay</p>

        <?php if($error) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
        <?php if($success) echo '<div class="alert alert-success">'.$success.'</div>'; ?>

        <form method="POST">
            <div class="input-group">
                <label>Họ và tên</label>
                <input type="text" name="name" placeholder="Nguyễn Văn A" required>
            </div>
            <div class="input-group">
                <label>Mã sinh viên</label>
                <input type="text" name="student_code" placeholder="D124801">
            </div>
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@fbu.edu.vn" required>
            </div>
            <div class="input-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Ít nhất 6 ký tự" required>
            </div>
            <div class="input-group">
                <label>Nhập lại mật khẩu</label>
                <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng ký</button>
        </form>

        <div class="footer-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập</a>
        </div>
    </div>
</body>
</html>