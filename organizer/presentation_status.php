<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'organizer') {
    header("Location: " . base_url("index.php"));
    exit;
}

if(isset($_POST['update'])) {
    $pid = intval($_POST['paper_id']);
    $status = clean($_POST['presentation_status']);
    $conn->query("UPDATE papers SET presentation_status='$status' WHERE id=$pid");
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Đã cập nhật trạng thái!'];
    header("Location: presentation_status.php");
    exit;
}

 $papers = $conn->query("SELECT p.id, p.title, p.presentation_status, u.name FROM papers p JOIN users u ON p.user_id = u.id WHERE p.status = 'approved'");

 $pageTitle = "Trạng thái trình bày";
include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-bottom: 1px solid var(--border);">
        <h2 style="font-size: 20px; font-weight: 700;">Cập nhật trạng thái trình bày</h2>
    </header>

    <div style="padding: 30px;">
        <div class="card">
            <div class="table-container">
                <table>
                    <thead><tr><th>Tác giả</th><th>Bài tham luận</th><th>Trạng thái hiện tại</th><th>Thay đổi</th></tr></thead>
                    <tbody>
                        <?php if($papers->num_rows > 0): ?>
                            <?php while($p = $papers->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p['name']); ?></td>
                                <td><?php echo htmlspecialchars($p['title']); ?></td>
                                <td>
                                    <span class="badge badge-info"><?php echo $p['presentation_status']; ?></span>
                                </td>
                                <td>
                                    <form method="POST" style="display: flex; gap: 10px; align-items: center;">
                                        <input type="hidden" name="paper_id" value="<?php echo $p['id']; ?>">
                                        <select name="presentation_status" style="padding: 8px; background: var(--bg); border: 1px solid var(--border); color: var(--fg); border-radius: 6px;">
                                            <option value="not_started" <?php echo $p['presentation_status']=='not_started'?'selected':''; ?>>Chưa trình bày</option>
                                            <option value="presented" <?php echo $p['presentation_status']=='presented'?'selected':''; ?>>Đã trình bày</option>
                                            <option value="absent" <?php echo $p['presentation_status']=='absent'?'selected':''; ?>>Vắng mặt</option>
                                        </select>
                                        <button name="update" class="btn btn-primary" style="padding: 8px 12px;">Cập nhật</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align: center; color: var(--fg-muted);">Không có bài nào đã duyệt.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>