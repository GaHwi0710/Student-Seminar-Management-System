<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'organizer') {
    header("Location: " . base_url("index.php"));
    exit;
}

if(isset($_GET['cancel'])) {
    $id = intval($_GET['cancel']);
    $conn->query("DELETE FROM registrations WHERE id = $id");
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Đã hủy đăng ký!'];
    header("Location: manage_regs.php");
    exit;
}

 $filter = "";
 $seminar_id_get = isset($_GET['seminar_id']) ? intval($_GET['seminar_id']) : 0;
if($seminar_id_get > 0) {
    $filter = " AND r.seminar_id = $seminar_id_get";
}

 $regs = $conn->query("SELECT r.id, r.status, r.registered_at, u.name as student_name, u.student_code, s.title 
                      FROM registrations r 
                      JOIN users u ON r.user_id = u.id 
                      JOIN seminars s ON r.seminar_id = s.id 
                      WHERE 1 $filter
                      ORDER BY r.registered_at DESC");

 $seminars = $conn->query("SELECT id, title FROM seminars");
 $pageTitle = "Quản lý Đăng ký";

include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 20px; font-weight: 700;">Danh sách đăng ký tham dự</h2>
            <form method="GET" style="display: flex; gap: 10px;">
                <select name="seminar_id" onchange="this.form.submit()" style="padding: 8px; background: var(--bg); border: 1px solid var(--border); color: var(--fg); border-radius: 6px;">
                    <option value="0">Tất cả hội thảo</option>
                    <?php 
                    $seminars->data_seek(0);
                    while($s = $seminars->fetch_assoc()): 
                    ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo $seminar_id_get == $s['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($s['title']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </form>
        </div>
    </header>

    <div style="padding: 30px;">
        <div class="card">
            <div class="table-container">
                <table>
                    <thead><tr><th>Sinh viên</th><th>Mã SV</th><th>Hội thảo</th><th>Ngày đăng ký</th><th>Trạng thái</th><th>Hành động</th></tr></thead>
                    <tbody>
                        <?php if($regs->num_rows > 0): ?>
                            <?php while($r = $regs->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($r['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($r['student_code']); ?></td>
                                <td><?php echo htmlspecialchars($r['title']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($r['registered_at'])); ?></td>
                                <td><span class="badge badge-success"><?php echo $r['status']; ?></span></td>
                                <td>
                                    <a href="?cancel=<?php echo $r['id']; ?>&seminar_id=<?php echo $seminar_id_get; ?>" 
                                       class="btn btn-danger" 
                                       style="padding: 6px 12px; font-size: 12px;"
                                       onclick="return confirm('Hủy đăng ký này?')">
                                        Hủy
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center; color: var(--fg-muted);">Không có dữ liệu</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>