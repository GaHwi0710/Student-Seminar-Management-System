<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'admin') {
    header("Location: " . base_url("index.php"));
    exit;
}

if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id AND role = 'organizer'");
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Đã xóa thành viên BTC!'];
    header("Location: manage_organizers.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $pass = password_hash('123456', PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$pass', 'organizer')";
    if($conn->query($sql)) {
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Thêm BTC thành công!'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Lỗi: Email có thể đã tồn tại.'];
    }
    header("Location: manage_organizers.php");
    exit;
}

 $organizers = $conn->query("SELECT * FROM users WHERE role = 'organizer' ORDER BY id DESC");
 $pageTitle = "Quản lý Ban Tổ Chức";

include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <h2 style="font-size: 20px; font-weight: 700;">Quản lý Ban Tổ Chức</h2>
    </header>

    <div style="padding: 30px;">
        <div class="card" style="margin-bottom: 20px;">
            <h3 style="margin-bottom: 15px; font-size: 16px;">Thêm thành viên BTC mới</h3>
            <form method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: end;">
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Họ tên</label>
                        <input type="text" name="name" required placeholder="Nguyễn Văn A">
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Email</label>
                        <input type="email" name="email" required placeholder="btc@fbu.edu.vn">
                    </div>
                    <button class="btn btn-primary">Thêm mới</button>
                </div>
                <p style="font-size: 12px; color: var(--fg-muted); margin-top: 10px;">* Mật khẩu mặc định: 123456</p>
            </form>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 15px; font-size: 16px;">Danh sách Ban Tổ Chức</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($organizers->num_rows > 0):
                            while($o = $organizers->fetch_assoc()): 
                        ?>
                        <tr>
                            <td><?php echo $o['id']; ?></td>
                            <td><?php echo htmlspecialchars($o['name']); ?></td>
                            <td><?php echo htmlspecialchars($o['email']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($o['created_at'])); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $o['id']; ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; margin-right: 5px;">Sửa</a>
                                <a href="?delete=<?php echo $o['id']; ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--fg-muted);">Chưa có thành viên BTC nào.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>