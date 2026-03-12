<?php 
require '../includes/config.php';
requireLogin();

 $user_id = $_SESSION['user_id'];

 $sql_regs = "SELECT COUNT(*) as total FROM registrations WHERE user_id = $user_id";
 $regs = $conn->query($sql_regs)->fetch_assoc()['total'];

 $sql_papers = "SELECT COUNT(*) as total FROM papers WHERE user_id = $user_id";
 $papers = $conn->query($sql_papers)->fetch_assoc()['total'];

 $seminars = $conn->query("SELECT * FROM seminars WHERE status = 'open' ORDER BY event_date ASC LIMIT 4");

 $pageTitle = "Tổng quan";

include '../includes/header.php'; 

include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div>
            <h2 id="page-title" style="font-size: 20px; font-weight: 700;">Tổng quan</h2>
            <p id="page-subtitle" style="font-size: 12px; color: var(--fg-muted);">Chào mừng <?php echo $_SESSION['user_name']; ?></p>
        </div>
        <div style="display: flex; gap: 15px;">
            <button class="btn btn-secondary" onclick="showToast('Chào mừng đến với FBU Seminar!', 'success')">Thông báo</button>
        </div>
    </header>

    <div id="content-area" style="padding: 30px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="card" style="border-left: 4px solid var(--accent);">
                <div style="font-size: 28px; font-weight: 700;"><?php echo $regs; ?></div>
                <div style="color: var(--fg-muted); font-size: 13px;">Hội thảo đã đăng ký</div>
            </div>
            <div class="card" style="border-left: 4px solid var(--success);">
                <div style="font-size: 28px; font-weight: 700;"><?php echo $papers; ?></div>
                <div style="color: var(--fg-muted); font-size: 13px;">Bài tham luận</div>
            </div>
        </div>

        <h3 style="margin-bottom: 15px;">Hội thảo sắp tới</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php 
            if ($seminars && $seminars->num_rows > 0) {
                while($s = $seminars->fetch_assoc()): 
            ?>
            <div class="card" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location.href='seminars.php'">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span class="badge badge-success"><?php echo strtoupper($s['status']); ?></span>
                    <span style="color: var(--fg-muted); font-size: 12px;"><?php echo $s['event_date']; ?></span>
                </div>
                <h4 style="margin-bottom: 8px; font-size: 16px;"><?php echo $s['title']; ?></h4>
                <p style="font-size: 13px; color: var(--fg-muted); margin-bottom: 15px;"><?php echo substr($s['description'], 0, 100); ?>...</p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 5px; color: var(--info); font-size: 12px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <?php echo $s['location']; ?>
                    </div>
                    <span style="font-size: 12px; color: var(--warning);"><?php echo $s['capacity']; ?> slot</span>
                </div>
            </div>
            <?php 
                endwhile;
            } else {
                echo "<p style='color: var(--fg-muted);'>Không có hội thảo nào sắp tới.</p>";
            }
            ?>
        </div>
    </div>
</main>

<?php 
checkToast();
include '../includes/footer.php'; 
?>