<?php
require 'includes/config.php';

if (isLoggedIn()) {
    $role = $_SESSION['user_role'];
    if ($role == 'admin') header("Location: admin/dashboard.php");
    elseif ($role == 'organizer') header("Location: organizer/dashboard.php");
    else header("Location: student/dashboard.php");
    exit;
}

 $error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean($_POST['email']);
    $pass = $_POST['password'];
    
    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();
        if (password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_code'] = $user['student_code'] ?? $user['email'];
            
            if ($user['role'] == 'admin') header("Location: admin/dashboard.php");
            elseif ($user['role'] == 'organizer') header("Location: organizer/dashboard.php");
            else header("Location: student/dashboard.php");
            exit;
        }
    }
    $error = "Email hoặc mật khẩu không đúng!";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>FBU Seminar - Đăng nhập</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #0c1015; --bg-secondary: #141a22; --fg: #e8edf4; --fg-muted: #6b7a8f; --accent: #d4a056; --accent-hover: #e5b367; --border: #252f3d; --danger: #f87171; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--fg); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .bg-mesh { position: fixed; inset: 0; z-index: -1; background: radial-gradient(ellipse at 20% 30%, rgba(212, 160, 86, 0.08) 0%, transparent 50%), linear-gradient(180deg, var(--bg) 0%, var(--bg-secondary) 100%); }
        .glass-panel { background: rgba(21, 28, 37, 0.9); backdrop-filter: blur(12px); border: 1px solid var(--border); width: 100%; max-width: 450px; border-radius: 20px; padding: 40px; }
        h1 { text-align: center; font-size: 24px; margin-bottom: 5px; }
        .subtitle { text-align: center; color: var(--fg-muted); font-size: 14px; margin-bottom: 25px; }
        .input-group { margin-bottom: 1rem; }
        .input-group label { display: block; font-size: 13px; color: var(--fg-muted); margin-bottom: 6px; }
        .input-group input { width: 100%; padding: 12px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; color: var(--fg); font-size: 14px; }
        .input-group input:focus { outline: none; border-color: var(--accent); }
        .btn { width: 100%; padding: 12px; border-radius: 8px; background: var(--accent); color: #000; border: none; cursor: pointer; font-weight: 600; transition: 0.2s; }
        .btn:hover { background: var(--accent-hover); transform: translateY(-1px); }
        .alert { padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; background: rgba(248, 113, 113, 0.1); color: var(--danger); }
        .links { margin-top: 20px; display: flex; justify-content: space-between; font-size: 13px; }
        .links a { color: var(--accent); text-decoration: none; }
       
        .logo-box { 
            width: 80px;  
            height: 80px; 
            background: transparent; 
            border-radius: 20px; 
            margin: 0 auto 15px; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            overflow: hidden; 
        }
        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain; 
        }
    </style>
</head>
<body>
    <div class="bg-mesh"></div>

    <div class="glass-panel">
        <div class="logo-box">
            <img src="assets/img/logo.png" alt="FBU Logo">
        </div>
        
        <h1>FBU Seminar</h1>
        <p class="subtitle">Hệ thống Quản lý Hội thảo</p>

        <?php if($error) echo '<div class="alert">'.$error.'</div>'; ?>

        <form method="POST">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Nhập email..." required>
            </div>
            <div class="input-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
            </div>
            <button type="submit" class="btn">Đăng nhập</button>
        </form>

        <div class="links">
            <a href="forgot_password.php">Quên mật khẩu?</a>
            <a href="register.php">Đăng ký tài khoản</a>
        </div>
    </div>
</body>
</html>