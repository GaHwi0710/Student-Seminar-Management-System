<?php 
require '../config.php';
checkRole('organizer');

if(isset($_POST['update_time'])) {
    $paper_id = intval($_POST['paper_id']);
    $time = clean($_POST['presentation_time']);
    $conn->query("UPDATE papers SET presentation_time = '$time' WHERE id = $paper_id");
    header("Location: set_schedule.php");
}

 $papers = $conn->query("SELECT p.id, p.title, p.presentation_time, u.name as author, s.title as sem_title 
                        FROM papers p 
                        JOIN users u ON p.user_id = u.id 
                        JOIN seminars s ON p.seminar_id = s.id 
                        WHERE p.status = 'approved'");
?>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <div class="header"><h2>Thiết lập lịch trình báo cáo</h2></div>
    
    <div class="card">
        <table>
            <thead><tr><th>Bài tham luận</th><th>Tác giả</th><th>Hội thảo</th><th>Thời gian báo cáo</th><th>Thao tác</th></tr></thead>
            <tbody>
                <?php while($p = $papers->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $p['title']; ?></td>
                    <td><?php echo $p['author']; ?></td>
                    <td><?php echo $p['sem_title']; ?></td>
                    <td>
                        <form method="POST" style="display:flex; gap:5px;">
                            <input type="hidden" name="paper_id" value="<?php echo $p['id']; ?>">
                            <input type="datetime-local" name="presentation_time" class="form-control" value="<?php echo $p['presentation_time']; ?>">
                            <button name="update_time" class="btn btn-primary btn-sm">Đặt</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>