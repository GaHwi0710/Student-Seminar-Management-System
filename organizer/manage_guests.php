<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'organizer') {
    header("Location: " . base_url("index.php"));
    exit;
}

if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM guests WHERE id = $id");
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Đã xóa khách mời!'];
    header("Location: manage_guests.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sem_id = intval($_POST['seminar_id']);
    $name = clean($_POST['name']);
    $org = clean($_POST['organization']);
    $pos = clean($_POST['position']);
    
    $conn->query("INSERT INTO guests (seminar_id, name, organization, position) VALUES ($sem_id, '$name', '$org', '$pos')");
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Thêm khách mời thành công!'];
    header("Location: manage_guests.php");
    exit;
}

 $seminars = $conn->query("SELECT id, title FROM seminars");
 $guests = $conn->query("SELECT g.*, s.title as sem_title FROM guests g JOIN seminars s ON g.seminar_id = s.id ORDER BY g.id DESC");

 $pageTitle = "Quản lý Khách mời";
include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <h2 style="font-size: 20px; font-weight: 700;">Quản lý Khách mời</h2>
    </header>

    <div style="padding: 30px;">
        <div class="card" style="margin-bottom: 20px;">
            <h3 style="margin-bottom: 15px; font-size: 16px;">Thêm khách mời mới</h3>
            <form method="POST">
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Hội thảo</label>
                        <select name="seminar_id" required>
                            <?php while($s = $seminars->fetch_assoc()): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['title']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Tên khách mời</label>
                        <input type="text" name="name" required placeholder="TS. Nguyễn Văn A">
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Tổ chức</label>
                        <input type="text" name="organization" placeholder="ĐH FBU">
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Chức vụ</label>
                        <input type="text" name="position" placeholder="Giảng viên">
                    </div>
                    <button class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 15px; font-size: 16px;">Danh sách khách mời</h3>
            <div class="table-container">
                <table>
                    <thead><tr><th>Tên</th><th>Chức vụ</th><th>Tổ chức</th><th>Hội thảo</th><th>Hành động</th></tr></thead>
                    <tbody>
                        <?php while($g = $guests->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($g['name']); ?></td>
                            <td><?php echo htmlspecialchars($g['position']); ?></td>
                            <td><?php echo htmlspecialchars($g['organization']); ?></td>
                            <td><?php echo htmlspecialchars($g['sem_title']); ?></td>
                            <td>
                                <a href="?delete=<?php echo $g['id']; ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Xóa khách mời này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>