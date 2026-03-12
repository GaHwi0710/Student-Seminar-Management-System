<?php 
require '../includes/config.php';
requireLogin();

 $user_id = $_SESSION['user_id'];
 $pageTitle = "Hội thảo đã đăng ký";

 $sql = "SELECT r.registered_at, s.title, s.event_date, s.location, s.status 
        FROM registrations r 
        JOIN seminars s ON r.seminar_id = s.id 
        WHERE r.user_id = $user_id 
        ORDER BY r.registered_at DESC";
 $registrations = $conn->query($sql);

include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div>
            <h2 id="page-title" style="font-size: 20px; font-weight: 700;">Đăng ký của tôi</h2>
            <p id="page-subtitle" style="font-size: 12px; color: var(--fg-muted);">Lịch sử đăng ký tham gia hội thảo</p>
        </div>
    </header>

    <div id="content-area" style="padding: 30px;">
        <div class="card" style="overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                        <th style="text-align: left; padding: 15px; font-size: 13px; color: var(--fg-muted);">Tên hội thảo</th>
                        <th style="text-align: left; padding: 15px; font-size: 13px; color: var(--fg-muted);">Ngày tổ chức</th>
                        <th style="text-align: left; padding: 15px; font-size: 13px; color: var(--fg-muted);">Địa điểm</th>
                        <th style="text-align: left; padding: 15px; font-size: 13px; color: var(--fg-muted);">Ngày đăng ký</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($registrations && $registrations->num_rows > 0) {
                        while($row = $registrations->fetch_assoc()):
                    ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 15px; font-weight: 600;"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td style="padding: 15px; color: var(--fg-muted);"><?php echo date('d/m/Y', strtotime($row['event_date'])); ?></td>
                        <td style="padding: 15px; color: var(--fg-muted);"><?php echo htmlspecialchars($row['location']); ?></td>
                        <td style="padding: 15px; color: var(--fg-muted);"><?php echo date('d/m/Y H:i', strtotime($row['registered_at'])); ?></td>
                    </tr>
                    <?php 
                        endwhile;
                    } else {
                        echo "<tr><td colspan='4' style='text-align: center; padding: 40px; color: var(--fg-muted);'>Bạn chưa đăng ký hội thảo nào.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php 
include '../includes/footer.php'; 
?>