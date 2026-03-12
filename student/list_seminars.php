<?php 
require '../includes/config.php';
requireLogin();

 $user_id = $_SESSION['user_id'];
 $pageTitle = "Danh sách hội thảo";

 $sql = "SELECT * FROM seminars WHERE status = 'open' ORDER BY event_date ASC";
 $seminars = $conn->query($sql);

include '../includes/header.php'; 

include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div>
            <h2 id="page-title" style="font-size: 20px; font-weight: 700;">Danh sách hội thảo</h2>
            <p id="page-subtitle" style="font-size: 12px; color: var(--fg-muted);">Khám phá và đăng ký tham gia</p>
        </div>
    </header>

    <div id="content-area" style="padding: 30px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
            <?php 
            if ($seminars && $seminars->num_rows > 0) {
                while($s = $seminars->fetch_assoc()):
                    $check_reg = $conn->query("SELECT id FROM registrations WHERE user_id = $user_id AND seminar_id = ".$s['id']);
                    $is_registered = $check_reg->num_rows > 0;
                    
                    $count_reg = $conn->query("SELECT COUNT(*) as total FROM registrations WHERE seminar_id = ".$s['id'])->fetch_assoc()['total'];
                    $slots_left = $s['capacity'] - $count_reg;
            ?>
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span class="badge badge-success"><?php echo strtoupper($s['status']); ?></span>
                        <span style="color: var(--fg-muted); font-size: 12px;"><?php echo date('d/m/Y', strtotime($s['event_date'])); ?></span>
                    </div>
                    <h4 style="margin-bottom: 8px; font-size: 17px;"><?php echo htmlspecialchars($s['title']); ?></h4>
                    <p style="font-size: 13px; color: var(--fg-muted); margin-bottom: 10px; line-height: 1.5;">
                        <?php echo htmlspecialchars(substr($s['description'], 0, 120)); ?>...
                    </p>
                    <div style="font-size: 13px; color: var(--info); margin-bottom: 15px;">
                        📍 Địa điểm: <?php echo htmlspecialchars($s['location']); ?>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                    <span style="font-size: 13px; color: <?php echo $slots_left > 0 ? 'var(--success)' : 'var(--danger)'; ?>">
                        Còn <?php echo $slots_left; ?> chỗ
                    </span>
                    
                    <?php if($is_registered): ?>
                        <button class="btn btn-secondary" disabled style="cursor: not-allowed; opacity: 0.7;">Đã đăng ký</button>
                    <?php elseif($slots_left <= 0): ?>
                        <button class="btn btn-secondary" disabled style="cursor: not-allowed; opacity: 0.7;">Hết chỗ</button>
                    <?php else: ?>
                        <a href="register_event.php?id=<?php echo $s['id']; ?>" class="btn" style="background: var(--accent); color: #fff; text-decoration: none;">
                            Đăng ký ngay
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php 
                endwhile;
            } else {
                echo "<p style='color: var(--fg-muted); grid-column: 1/-1; text-align: center;'>Hiện không có hội thảo nào mở đăng ký.</p>";
            }
            ?>
        </div>
    </div>
</main>

<?php 
include '../includes/footer.php'; 
?>