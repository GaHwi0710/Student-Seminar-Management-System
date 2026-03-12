<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'organizer') {
    header("Location: " . base_url("index.php"));
    exit;
}

if(isset($_POST['update_status'])) {
    $pid = intval($_POST['paper_id']);
    $status = clean($_POST['status']);
    $reason = clean($_POST['reason'] ?? '');
    
    $conn->query("UPDATE papers SET status='$status', rejection_reason='$reason' WHERE id=$pid");
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Đã cập nhật trạng thái bài tham luận!'];
    header("Location: manage_papers.php");
    exit;
}

 $papers = $conn->query("SELECT p.*, u.name, s.title as sem_title FROM papers p 
                        JOIN users u ON p.user_id = u.id 
                        JOIN seminars s ON p.seminar_id = s.id 
                        ORDER BY p.submitted_at DESC");

 $pageTitle = "Quản lý bài tham luận";

include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div>
            <h2 style="font-size: 20px; font-weight: 700;">Quản lý bài tham luận</h2>
            <p style="font-size: 12px; color: var(--fg-muted);">Xét duyệt các bài nộp từ sinh viên</p>
        </div>
    </header>

    <div style="padding: 30px;">
        <div class="card">
            <h3 style="margin-bottom: 15px; font-size: 16px;">Danh sách bài nộp</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tác giả</th>
                            <th>Tiêu đề bài tham luận</th>
                            <th>Hội thảo</th>
                            <th>File</th>
                            <th>Trạng thái</th>
                            <th style="width: 300px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($papers->num_rows > 0):
                            while($p = $papers->fetch_assoc()): 
                                $badge_class = 'badge-warning';
                                if($p['status'] == 'approved') $badge_class = 'badge-success';
                                if($p['status'] == 'rejected') $badge_class = 'badge-danger';
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                            <td><b><?php echo htmlspecialchars($p['title']); ?></b></td>
                            <td><?php echo htmlspecialchars($p['sem_title']); ?></td>
                            <td>
                                <a href="<?php echo base_url($p['file_path']); ?>" target="_blank" class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;">
                                    Tải xuống
                                </a>
                            </td>
                            <td><span class="badge <?php echo $badge_class; ?>"><?php echo strtoupper($p['status']); ?></span></td>
                            <td>
                                <form method="POST" style="display: flex; gap: 10px; align-items: center;">
                                    <input type="hidden" name="paper_id" value="<?php echo $p['id']; ?>">
                                    
                                    <?php if($p['status'] == 'pending'): ?>
                                        <select name="status" style="padding: 8px; background: var(--bg); border: 1px solid var(--border); color: var(--fg); border-radius: 6px;">
                                            <option value="approved">Duyệt</option>
                                            <option value="rejected">Từ chối</option>
                                        </select>
                                        <input type="text" name="reason" placeholder="Lý do (nếu từ chối)" style="padding: 8px; background: var(--bg); border: 1px solid var(--border); color: var(--fg); border-radius: 6px;">
                                        <button name="update_status" class="btn btn-primary" style="padding: 8px 12px;">Lưu</button>
                                    <?php else: ?>
                                        <span style="color: var(--fg-muted); font-size: 12px;">Đã xử lý</span>
                                        <?php if($p['rejection_reason']): ?>
                                            <span style="font-size: 11px; color: var(--danger);">(Lý do: <?php echo $p['rejection_reason']; ?>)</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--fg-muted);">Không có bài tham luận nào.</td>
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