<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'organizer') {
    header("Location: " . base_url("index.php"));
    exit;
}

if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM seminars WHERE id = $id");
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Đã xóa hội thảo!'];
    header("Location: manage_seminars.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean($_POST['title']);
    $location = clean($_POST['location']);
    $date = clean($_POST['event_date']);
    $time = clean($_POST['event_time']);
    $desc = clean($_POST['description']);
    $status = clean($_POST['status']);
    $capacity = intval($_POST['capacity'] ?? 100);
    $id = intval($_POST['id'] ?? 0);

    if ($id == 0) {
        $sql = "INSERT INTO seminars (title, description, location, event_date, event_time, status, capacity, created_by) 
                VALUES ('$title', '$desc', '$location', '$date', '$time', '$status', $capacity, {$_SESSION['user_id']})";
        if($conn->query($sql)) {
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Tạo hội thảo thành công!'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Lỗi: ' . $conn->error];
        }
    } else {
        $sql = "UPDATE seminars SET title='$title', description='$desc', location='$location', event_date='$date', event_time='$time', status='$status', capacity=$capacity WHERE id=$id";
        if($conn->query($sql)) {
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Cập nhật thành công!'];
        }
    }
    header("Location: manage_seminars.php");
    exit;
}

 $seminars = $conn->query("SELECT * FROM seminars ORDER BY event_date DESC");
 $pageTitle = "Quản lý hội thảo";

include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div>
            <h2 style="font-size: 20px; font-weight: 700;">Quản lý hội thảo</h2>
            <p style="font-size: 12px; color: var(--fg-muted);">Tạo và quản lý các buổi hội thảo</p>
        </div>
    </header>

    <div style="padding: 30px;">
        <div class="card" style="margin-bottom: 20px;">
            <h3 style="margin-bottom: 15px; font-size: 16px;">Tạo hội thảo mới</h3>
            <form method="POST">
                <input type="hidden" name="id" value="0">
                
                <div class="input-group">
                    <label>Tiêu đề hội thảo</label>
                    <input type="text" name="title" required placeholder="Ví dụ: Hội thảo AI trong Tài chính">
                </div>
                
                <div class="input-group">
                    <label>Mô tả ngắn</label>
                    <textarea name="description" rows="3" style="width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; color: var(--fg); padding: 12px;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Địa điểm</label>
                        <input type="text" name="location" required placeholder="Hội trường A">
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Ngày tổ chức</label>
                        <input type="date" name="event_date" required>
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Giờ bắt đầu</label>
                        <input type="time" name="event_time" required>
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Sức chứa</label>
                        <input type="number" name="capacity" value="100" required>
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Trạng thái</label>
                        <select name="status">
                            <option value="upcoming">Sắp tới</option>
                            <option value="open" selected>Mở đăng ký</option>
                            <option value="closed">Đóng</option>
                        </select>
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">Tạo mới</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 15px; font-size: 16px;">Danh sách hội thảo</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tên hội thảo</th>
                            <th>Ngày giờ</th>
                            <th>Địa điểm</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($seminars->num_rows > 0):
                            while($s = $seminars->fetch_assoc()): 
                                // Định dạng badge màu
                                $badge_class = 'badge-info';
                                if($s['status'] == 'open') $badge_class = 'badge-success';
                                if($s['status'] == 'closed') $badge_class = 'badge-danger';
                        ?>
                        <tr>
                            <td><b><?php echo htmlspecialchars($s['title']); ?></b></td>
                            <td><?php echo date('d/m/Y', strtotime($s['event_date'])); ?> - <?php echo $s['event_time']; ?></td>
                            <td><?php echo htmlspecialchars($s['location']); ?></td>
                            <td><span class="badge <?php echo $badge_class; ?>"><?php echo strtoupper($s['status']); ?></span></td>
                            <td>
                                <a href="edit_seminar.php?id=<?php echo $s['id']; ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; margin-right: 5px;">Sửa</a>
                                <a href="?delete=<?php echo $s['id']; ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Xóa hội thảo này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--fg-muted);">Chưa có hội thảo nào.</td>
                        </tr>
                        <?php endif; ?>
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