<?php 
require '../includes/config.php';
requireLogin();

 $seminars = $conn->query("SELECT * FROM seminars ORDER BY event_date DESC");
 $pageTitle = "Danh sách hội thảo";
include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-bottom: 1px solid var(--border);">
        <h2 style="font-size: 20px; font-weight: 700;">Danh sách hội thảo</h2>
        <p style="font-size: 12px; color: var(--fg-muted);">Tra cứu và đăng ký tham dự</p>
    </header>

    <div style="padding: 30px;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tên hội thảo</th>
                        <th>Ngày tổ chức</th>
                        <th>Địa điểm</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $seminars->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['event_date']; ?></td>
                        <td><?php echo $row['location']; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $row['status'] == 'open' ? 'success' : 'warning'; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="seminar_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Chi tiết & Đăng ký</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>