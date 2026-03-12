<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'admin') {
    header("Location: " . base_url("index.php"));
    exit;
}

 $id = intval($_GET['id'] ?? 0);
if (!$id) {
    header("Location: manage_students.php");
    exit;
}

 $user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if (!$user) {
    $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Không tìm thấy người dùng!'];
    header("Location: manage_students.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $role = clean($_POST['role']);
    $status = clean($_POST['status']);
    
    $conn->query("UPDATE users SET name='$name', email='$email', role='$role', status='$status' WHERE id=$id");
    
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Cập nhật thành công!'];
    
    $redirect = ($role == 'organizer') ? 'manage_organizers.php' : 'manage_students.php';
    header("Location: $redirect");
    exit;
}

 $pageTitle = "Chỉnh sửa tài khoản";
include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <h2 style="font-size: 20px; font-weight: 700;">Chỉnh sửa tài khoản</h2>
    </header>

    <div style="padding: 30px;">
        <div class="card" style="max-width: 600px;">
            <form method="POST">
                <div class="input-group">
                    <label>Họ tên</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Vai trò</label>
                    <select name="role">
                        <option value="student" <?php echo ($user['role'] == 'student') ? 'selected' : ''; ?>>Sinh viên</option>
                        <option value="organizer" <?php echo ($user['role'] == 'organizer') ? 'selected' : ''; ?>>Ban Tổ Chức</option>
                        <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Trạng thái</label>
                    <select name="status">
                        <option value="active" <?php echo ($user['status'] == 'active') ? 'selected' : ''; ?>>Hoạt động</option>
                        <option value="inactive" <?php echo ($user['status'] == 'inactive') ? 'selected' : ''; ?>>Khóa</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <a href="javascript:history.back()" class="btn btn-secondary">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>