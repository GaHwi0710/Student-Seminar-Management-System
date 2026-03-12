<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'organizer') {
    header("Location: " . base_url("index.php"));
    exit;
}

 $id = intval($_GET['id'] ?? 0);
 $seminar = $conn->query("SELECT * FROM seminars WHERE id = $id")->fetch_assoc();

if (!$seminar) {
    $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Không tìm thấy hội thảo!'];
    header("Location: manage_seminars.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean($_POST['title']);
    $desc = clean($_POST['description']);
    $loc = clean($_POST['location']);
    $date = clean($_POST['event_date']);
    $time = clean($_POST['event_time']);
    $status = clean($_POST['status']);
    $capacity = intval($_POST['capacity']);

    $sql = "UPDATE seminars SET title='$title', description='$desc', location='$loc', event_date='$date', event_time='$time', status='$status', capacity=$capacity WHERE id=$id";
    
    if($conn->query($sql)) {
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Cập nhật thành công!'];
        header("Location: manage_seminars.php");
        exit;
    }
}

 $pageTitle = "Chỉnh sửa hội thảo";

include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div>
            <h2 style="font-size: 20px; font-weight: 700;">Chỉnh sửa hội thảo</h2>
            <p style="font-size: 12px; color: var(--fg-muted);">Cập nhật thông tin: <?php echo htmlspecialchars($seminar['title']); ?></p>
        </div>
    </header>

    <div style="padding: 30px;">
        <div class="card" style="max-width: 800px;">
            <form method="POST">
                <div class="input-group">
                    <label>Tiêu đề hội thảo</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($seminar['title']); ?>" required>
                </div>
                
                <div class="input-group">
                    <label>Mô tả</label>
                    <textarea name="description" rows="4" style="width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; color: var(--fg); padding: 12px;"><?php echo htmlspecialchars($seminar['description']); ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Địa điểm</label>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($seminar['location']); ?>" required>
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Ngày tổ chức</label>
                        <input type="date" name="event_date" value="<?php echo $seminar['event_date']; ?>" required>
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Giờ bắt đầu</label>
                        <input type="time" name="event_time" value="<?php echo $seminar['event_time']; ?>" required>
                    </div>
                    <div class="input-group" style="margin-bottom: 0;">
                        <label>Sức chứa</label>
                        <input type="number" name="capacity" value="<?php echo $seminar['capacity']; ?>" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Trạng thái</label>
                    <select name="status">
                        <option value="upcoming" <?php echo $seminar['status']=='upcoming'?'selected':''; ?>>Sắp tới</option>
                        <option value="open" <?php echo $seminar['status']=='open'?'selected':''; ?>>Mở đăng ký</option>
                        <option value="closed" <?php echo $seminar['status']=='closed'?'selected':''; ?>>Đóng đăng ký</option>
                        <option value="completed" <?php echo $seminar['status']=='completed'?'selected':''; ?>>Đã kết thúc</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <a href="manage_seminars.php" class="btn btn-secondary">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php 
include '../includes/footer.php'; 
?>