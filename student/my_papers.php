<?php 
require '../includes/config.php';
requireLogin();
 $user_id = $_SESSION['user_id'];

 $papers = $conn->query("SELECT p.*, s.title as sem_title FROM papers p JOIN seminars s ON p.seminar_id = s.id WHERE p.user_id = $user_id ORDER BY p.submitted_at DESC");
 $pageTitle = "Bài tham luận";
include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-bottom: 1px solid var(--border);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 20px; font-weight: 700;">Bài tham luận của tôi</h2>
                <p style="font-size: 12px; color: var(--fg-muted);">Quản lý các bài đã nộp</p>
            </div>
            <a href="submit_paper.php" class="btn btn-primary">Nộp bài mới</a>
        </div>
    </header>

    <div style="padding: 30px;">
        <div class="card">
            <div class="table-container">
                <table>
                    <thead><tr><th>Tên bài</th><th>Hội thảo</th><th>Trạng thái</th><th>File</th></tr></thead>
                    <tbody>
                        <?php if($papers->num_rows > 0): ?>
                            <?php while($p = $papers->fetch_assoc()): ?>
                            <tr>
                                <td><b><?php echo htmlspecialchars($p['title']); ?></b></td>
                                <td><?php echo htmlspecialchars($p['sem_title']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $p['status'] == 'approved' ? 'success' : ($p['status'] == 'pending' ? 'warning' : 'danger'); ?>">
                                        <?php echo strtoupper($p['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if(!empty($p['file_path'])): ?>
                                        <a href="<?php echo base_url($p['file_path']); ?>" target="_blank" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">Tải về</a>
                                    <?php else: ?>
                                        <span style="color: var(--fg-muted);">N/A</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align: center; padding: 20px; color: var(--fg-muted);">Bạn chưa nộp bài nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>