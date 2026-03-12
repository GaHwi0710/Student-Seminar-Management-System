<?php 
require '../includes/config.php';
requireLogin();

 $user_id = $_SESSION['user_id'];
 $msg = "";
 $error = "";

if (!isset($_GET['id'])) {
    header("Location: seminars.php");
    exit;
}

 $id = intval($_GET['id']);
 $seminar = $conn->query("SELECT * FROM seminars WHERE id = $id")->fetch_assoc();

if (!$seminar) {
    die("Không tìm thấy hội thảo.");
}

if (isset($_POST['register'])) {
    $check = $conn->query("SELECT id FROM registrations WHERE user_id = $user_id AND seminar_id = $id");
    if ($check->num_rows > 0) {
        $error = "Bạn đã đăng ký hội thảo này rồi!";
    } else {
        $conn->query("INSERT INTO registrations (user_id, seminar_id) VALUES ($user_id, $id)");
        $msg = "Đăng ký thành công!";
        $conn->query("UPDATE seminars SET capacity = capacity - 1 WHERE id = $id");
        $seminar = $conn->query("SELECT * FROM seminars WHERE id = $id")->fetch_assoc();
    }
}

if (isset($_POST['cancel'])) {
    $conn->query("DELETE FROM registrations WHERE user_id = $user_id AND seminar_id = $id");
    $conn->query("UPDATE seminars SET capacity = capacity + 1 WHERE id = $id");
    $msg = "Đã hủy đăng ký.";
    $seminar = $conn->query("SELECT * FROM seminars WHERE id = $id")->fetch_assoc();
}

 $is_registered = $conn->query("SELECT id FROM registrations WHERE user_id = $user_id AND seminar_id = $id")->num_rows > 0;

 $pageTitle = $seminar['title'];
include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-bottom: 1px solid var(--border);">
        <a href="seminars.php" style="color: var(--accent); text-decoration: none; display: flex; align-items: center; gap: 5px; margin-bottom: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Quay lại danh sách
        </a>
        <h2 style="font-size: 24px; font-weight: 700;"><?php echo $seminar['title']; ?></h2>
    </header>

    <div style="padding: 30px;">
        <?php if($msg) echo '<div class="alert alert-success" style="padding: 15px; background: rgba(52, 211, 153, 0.1); color: var(--success); border-radius: 8px; margin-bottom: 20px;">'.$msg.'</div>'; ?>
        <?php if($error) echo '<div class="alert alert-danger" style="padding: 15px; background: rgba(248, 113, 113, 0.1); color: var(--danger); border-radius: 8px; margin-bottom: 20px;">'.$error.'</div>'; ?>

        <div class="card">
            <div style="margin-bottom: 20px;">
                <h3 style="color: var(--accent); margin-bottom: 10px;">Thông tin chi tiết</h3>
                <p style="color: var(--fg-muted); line-height: 1.6;"><?php echo nl2br($seminar['description']); ?></p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; border-top: 1px solid var(--border); padding-top: 20px;">
                <div>
                    <p style="font-size: 12px; color: var(--fg-muted);">Thời gian</p>
                    <p style="font-weight: 600;"><?php echo $seminar['event_date'] . ' lúc ' . date('H:i', strtotime($seminar['event_time'])); ?></p>
                </div>
                <div>
                    <p style="font-size: 12px; color: var(--fg-muted);">Địa điểm</p>
                    <p style="font-weight: 600;"><?php echo $seminar['location']; ?></p>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 20px;">
                <div>
                    <p style="font-size: 12px; color: var(--fg-muted);">Số chỗ còn lại</p>
                    <p style="font-size: 20px; font-weight: 700; color: var(--warning);"><?php echo $seminar['capacity']; ?> chỗ</p>
                </div>
                
                <div>
                    <?php if ($is_registered): ?>
                        <form method="POST">
                            <button type="submit" name="cancel" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn hủy đăng ký?')">Hủy đăng ký</button>
                        </form>
                    <?php else: ?>
                        <form method="POST">
                            <button type="submit" name="register" class="btn btn-primary">Đăng ký tham dự</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>