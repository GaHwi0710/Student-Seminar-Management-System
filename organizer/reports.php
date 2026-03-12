<?php 
require '../config.php';
checkRole('organizer');
include 'sidebar.php';

 $stats = $conn->query("SELECT s.title, 
                          COUNT(DISTINCT r.id) as total_regs,
                          COUNT(DISTINCT p.id) as total_papers,
                          SUM(CASE WHEN p.status = 'approved' THEN 1 ELSE 0 END) as approved_papers
                       FROM seminars s 
                       LEFT JOIN registrations r ON s.id = r.seminar_id
                       LEFT JOIN papers p ON s.id = p.seminar_id
                       GROUP BY s.id");
?>
<div class="main-content">
    <div class="header"><h2>Báo cáo Thống kê</h2></div>
    
    <div class="card">
        <table>
            <thead><tr><th>Hội thảo</th><th>Số người đăng ký</th><th>Số bài nộp</th><th>Đã duyệt</th></tr></thead>
            <tbody>
                <?php while($row = $stats->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['total_regs']; ?></td>
                    <td><?php echo $row['total_papers']; ?></td>
                    <td><?php echo $row['approved_papers']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>