<?php 
require '../includes/config.php';
requireLogin();
 $user_id = $_SESSION['user_id'];

 $user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
 $msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $phone = clean($_POST['phone']);
    $class = clean($_POST['class']);
    
    $pass_sql = "";
    if(!empty($_POST['new_password'])) {
        $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $pass_sql = ", password='$new_pass'";
    }
    
    $conn->query("UPDATE users SET name='$name', phone='$phone', class='$class' $pass_sql WHERE id=$user_id");
    $_SESSION['user_name'] = $name; 
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Cập nhật thành công!'];
    header("Location: profile.php");
    exit;
}

 $pageTitle = "Thông tin cá nhân";
include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-bottom: 1px solid var(--border);">
        <h2 style="font-size: 20px; font-weight: 700;">Thông tin cá nhân</h2>
    </header>

    <div style="padding: 30px;">
        <div class="card" style="max-width: 600px;">
            <form method="POST">
                <div class="input-group">
                    <label>Họ tên</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                </div>
                <div class="input-group">
                    <label>Email (Không thể thay đổi)</label>
                    <input type="email" value="<?php echo $user['email']; ?>" disabled style="opacity: 0.7;">
                </div>
                <div class="input-group">
                    <label>Lớp</label>
                    <input type="text" name="class" value="<?php echo htmlspecialchars($user['class']); ?>">
                </div>
                <div class="input-group">
                    <label>SĐT</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
                
                <hr style="border-color: var(--border); margin: 20px 0;">
                <div class="input-group">
                    <label>Mật khẩu mới (Để trống nếu không đổi)</label>
                    <input type="password" name="new_password" placeholder="Nhập mật khẩu mới...">
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>