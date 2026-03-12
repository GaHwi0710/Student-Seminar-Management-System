<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'organizer') {
    header("Location: " . base_url("index.php"));
    exit;
}

 $total_seminars = $conn->query("SELECT COUNT(*) as total FROM seminars")->fetch_assoc()['total'];
 $total_papers = $conn->query("SELECT COUNT(*) as total FROM papers WHERE status = 'pending'")->fetch_assoc()['total'];

 $pageTitle = "Ban Tổ Chức";

include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div>
            <h2 style="font-size: 20px; font-weight: 700;">Trang Ban Tổ Chức</h2>
            <p style="font-size: 12px; color: var(--fg-muted);">Quản lý hội thảo và bài tham luận</p>
        </div>
    </header>

    <div id="content-area" style="padding: 30px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="card" style="border-left: 4px solid var(--info);">
                <div style="font-size: 28px; font-weight: 700;"><?php echo $total_seminars; ?></div>
                <div style="color: var(--fg-muted); font-size: 13px;">Hội thảo đã tạo</div>
            </div>
            <div class="card" style="border-left: 4px solid var(--warning);">
                <div style="font-size: 28px; font-weight: 700;"><?php echo $total_papers; ?></div>
                <div style="color: var(--fg-muted); font-size: 13px;">Bài chờ duyệt</div>
            </div>
        </div>

        <div class="card">
            <div style="font-weight: 600; margin-bottom: 15px;">Công việc cần làm</div>
            <div style="display: flex; gap: 15px;">
                <a href="<?php echo base_url('organizer/manage_seminars.php'); ?>" class="btn btn-primary">Tạo hội thảo mới</a>
                <a href="<?php echo base_url('organizer/manage_papers.php'); ?>" class="btn btn-secondary">Duyệt bài tham luận</a>
            </div>
        </div>
    </div>
</main>

<?php 
checkToast();
include '../includes/footer.php'; 
?>