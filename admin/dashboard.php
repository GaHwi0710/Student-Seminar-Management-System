<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'admin') {
    header("Location: " . base_url("index.php"));
    exit;
}

 $total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
 $total_seminars = $conn->query("SELECT COUNT(*) as total FROM seminars")->fetch_assoc()['total'];

 $pageTitle = "Admin Dashboard";

include '../includes/header.php'; 

include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div>
            <h2 style="font-size: 20px; font-weight: 700;">Quản trị hệ thống</h2>
            <p style="font-size: 12px; color: var(--fg-muted);">Tổng quan dữ liệu</p>
        </div>
    </header>

    <div id="content-area" style="padding: 30px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="card" style="border-left: 4px solid var(--accent);">
                <div style="font-size: 28px; font-weight: 700;"><?php echo $total_users; ?></div>
                <div style="color: var(--fg-muted); font-size: 13px;">Người dùng</div>
            </div>
            <div class="card" style="border-left: 4px solid var(--success);">
                <div style="font-size: 28px; font-weight: 700;"><?php echo $total_seminars; ?></div>
                <div style="color: var(--fg-muted); font-size: 13px;">Hội thảo</div>
            </div>
        </div>

        <div class="card">
            <div style="font-weight: 600; margin-bottom: 15px;">Truy cập nhanh</div>
            <div style="display: flex; gap: 15px;">
                <a href="<?php echo base_url('admin/manage_students.php'); ?>" class="btn btn-secondary">Quản lý người dùng</a>
                <a href="<?php echo base_url('admin/reports.php'); ?>" class="btn btn-secondary">Xem báo cáo</a>
            </div>
        </div>
    </div>
</main>

<?php 
checkToast();
include '../includes/footer.php'; 
?>