<?php 
require '../includes/config.php';
requireLogin();

if ($_SESSION['user_role'] != 'admin') {
    header("Location: " . base_url("index.php"));
    exit;
}

 $stats = $conn->query("SELECT s.title, COUNT(r.id) as total_regs, s.capacity 
                       FROM seminars s LEFT JOIN registrations r ON s.id = r.seminar_id 
                       GROUP BY s.id");

if(isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=report_seminars.csv');
    $output = fopen("php://output", "w");
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($output, array('Hội thảo', 'Số người đăng ký', 'Sức chứa', 'Tỉ lệ'));
    
    $stats->data_seek(0);
    while($row = $stats->fetch_assoc()) {
        $percent = ($row['capacity'] > 0) ? round(($row['total_regs']/$row['capacity'])*100, 2) : 0;
        fputcsv($output, array($row['title'], $row['total_regs'], $row['capacity'], $percent . '%'));
    }
    fclose($output);
    exit();
}

 $pageTitle = "Báo cáo thống kê";

include '../includes/header.php'; 

include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-radius: 0; border-left: none; border-top: none; border-right: none;">
        <div>
            <h2 style="font-size: 20px; font-weight: 700;">Thống kê & Báo cáo</h2>
            <p style="font-size: 12px; color: var(--fg-muted);">Tổng hợp dữ liệu hội thảo</p>
        </div>
        <a href="?export=true" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Xuất báo cáo CSV
        </a>
    </header>

    <div id="content-area" style="padding: 30px;">
        <div class="card">
            <div style="margin-bottom: 15px; font-weight: 600;">Số lượng đăng ký theo hội thảo</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Hội thảo</th>
                            <th>Đã đăng ký</th>
                            <th>Sức chứa</th>
                            <th>Tỉ lệ lấp đầy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stats->data_seek(0);
                        if($stats->num_rows > 0):
                            while($s = $stats->fetch_assoc()): 
                                $percent = ($s['capacity'] > 0) ? round(($s['total_regs']/$s['capacity'])*100, 1) : 0;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['title']); ?></td>
                            <td><span class="badge badge-info"><?php echo $s['total_regs']; ?></span></td>
                            <td><?php echo $s['capacity']; ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="flex: 1; height: 6px; background: var(--bg); border-radius: 3px; overflow: hidden;">
                                        <div style="width: <?php echo min($percent, 100); ?>%; height: 100%; background: var(--success);"></div>
                                    </div>
                                    <span style="font-size: 12px; min-width: 40px;"><?php echo $percent; ?>%</span>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--fg-muted);">Chưa có dữ liệu hội thảo</td>
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