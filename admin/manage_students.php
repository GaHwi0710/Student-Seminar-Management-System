<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'admin') {
    header("Location: " . base_url("index.php"));
    exit;
}

if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id AND role = 'student'");
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Đã xóa sinh viên thành công!'];
    header("Location: manage_students.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $code = clean($_POST['student_code']);
    $class = clean($_POST['class']);
    $pass = password_hash('123456', PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (name, email, password, role, student_code, class) 
            VALUES ('$name', '$email', '$pass', 'student', '$code', '$class')";
    
    if($conn->query($sql)) {
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Thêm sinh viên thành công!'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Lỗi: ' . $conn->error];
    }
    header("Location: manage_students.php");
    exit;
}

 $students = $conn->query("SELECT * FROM users WHERE role = 'student' ORDER BY id DESC");
 $pageTitle = "Quản lý Sinh viên";

include '../includes/header.php'; 

include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div>
            <h2 style="font-size: 20px; font-weight: 700;">Quản lý Sinh viên</h2>
            <p style="font-size: 12px; color: var(--fg-muted);">Danh sách và quản lý tài khoản sinh viên</p>
        </div>
    </header>

    <div id="content-area" style="padding: 30px;">
        
        <div class="card" style="margin-bottom: 20px;">
            <h3 style="margin-bottom: 15px; font-size: 16px;">Thêm sinh viên mới</h3>
            <form method="POST">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Họ tên</label>
                        <input type="text" name="name" required placeholder="Nguyễn Văn A">
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Email</label>
                        <input type="email" name="email" required placeholder="email@fbu.edu.vn">
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Mã SV</label>
                        <input type="text" name="student_code" required placeholder="D124801">
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Lớp</label>
                        <input type="text" name="class" required placeholder="D12.48.02">
                    </div>
                    <div>
                        <button type="submit" name="add_student" class="btn btn-primary">Thêm mới</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 15px; font-size: 16px;">Danh sách sinh viên</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Mã SV</th>
                            <th>Lớp</th>
                            <th>Email</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($students->num_rows > 0) {
                            while($row = $students->fetch_assoc()): 
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['class']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-secondary" 
                                   style="padding: 6px 12px; font-size: 12px; margin-right: 5px;">
                                    Sửa
                                </a>
                                
                                <a href="?delete=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger" 
                                   style="padding: 6px 12px; font-size: 12px;"
                                   onclick="return confirm('Bạn có chắc muốn xóa?')">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center; color: var(--fg-muted);'>Không có dữ liệu</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php 
checkToast();
include '../includes/footer.php'; 
?>