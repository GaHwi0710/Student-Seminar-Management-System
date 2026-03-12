<?php 
require '../config.php';
checkRole('admin');

if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: manage_accounts.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $role = clean($_POST['role']);
    $id = intval($_POST['id'] ?? 0);
    
    if ($id == 0) {
        $pass = password_hash('123456', PASSWORD_DEFAULT); 
        $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$pass', '$role')");
    } else {
        $conn->query("UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$id");
    }
    header("Location: manage_accounts.php");
}

 $users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <div class="header"><h2>Quản lý tài khoản</h2></div>
    
    <div class="card">
        <h3>Thêm tài khoản mới</h3>
        <form method="POST">
            <input type="hidden" name="id" id="user_id" value="0">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px;">
                <input type="text" name="name" class="form-control" placeholder="Họ tên" required>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
                <select name="role" class="form-control">
                    <option value="student">Sinh viên</option>
                    <option value="organizer">Ban Tổ Chức</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>

    <div class="card">
        <table>
            <thead><tr><th>ID</th><th>Tên</th><th>Email</th><th>Vai trò</th><th>Thao tác</th></tr></thead>
            <tbody>
                <?php while($u = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo $u['name']; ?></td>
                    <td><?php echo $u['email']; ?></td>
                    <td><span class="badge badge-success"><?php echo $u['role']; ?></span></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $u['id']; ?>" class="btn btn-primary btn-sm">Sửa</a>
                        <a href="?delete=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa?')">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>