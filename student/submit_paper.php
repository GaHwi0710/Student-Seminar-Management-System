<?php 
require '../includes/config.php';
requireLogin();

 $user_id = $_SESSION['user_id'];
 $msg = "";
 $error = "";

 $registered_seminars = $conn->query("SELECT s.id, s.title FROM seminars s JOIN registrations r ON s.id = r.seminar_id WHERE r.user_id = $user_id AND s.status = 'open'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $seminar_id = intval($_POST['seminar_id']);
    $title = clean($_POST['title']);
    $abstract = clean($_POST['abstract']);
    
    if(isset($_FILES['paper']) && $_FILES['paper']['error'] == 0) {
        $file_name = basename($_FILES['paper']['name']);
        $file_tmp = $_FILES['paper']['tmp_name'];
        
        $target_dir = "../uploads/papers/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $new_file_name = time() . "_" . $file_name;
        $target_file = $target_dir . $new_file_name;
        
        if (move_uploaded_file($file_tmp, $target_file)) {
            $db_path = "uploads/papers/" . $new_file_name;
            
            $sql = "INSERT INTO papers (user_id, seminar_id, title, abstract, file_path, status) 
                    VALUES ($user_id, $seminar_id, '$title', '$abstract', '$db_path', 'pending')";
            if ($conn->query($sql)) {
                $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Nộp bài thành công! Vui lòng chờ duyệt.'];
                header("Location: my_papers.php");
                exit;
            } else {
                $error = "Lỗi CSDL: " . $conn->error;
            }
        } else {
            $error = "Lỗi upload file.";
        }
    } else {
        $error = "Vui lòng chọn file đính kèm.";
    }
}

 $pageTitle = "Nộp bài tham luận";
include '../includes/header.php'; 
include '../includes/sidebar.php';
?>

<main class="main-content">
    <header class="glass-panel" style="position: sticky; top: 0; z-index: 40; padding: 15px 30px; border-bottom: 1px solid var(--border);">
        <h2 style="font-size: 20px; font-weight: 700;">Nộp bài tham luận mới</h2>
    </header>

    <div style="padding: 30px;">
        <div class="card" style="max-width: 800px;">
            <?php if($error) echo '<div class="alert alert-danger" style="padding: 15px; background: rgba(248, 113, 113, 0.1); color: var(--danger); border-radius: 8px; margin-bottom: 20px;">'.$error.'</div>'; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label>Chọn hội thảo đã đăng ký</label>
                    <select name="seminar_id" required>
                        <option value="">-- Chọn hội thảo --</option>
                        <?php while($s = $registered_seminars->fetch_assoc()): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['title']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <label>Tên bài tham luận</label>
                    <input type="text" name="title" placeholder="Nhập tên bài..." required>
                </div>

                <div class="input-group">
                    <label>Tóm tắt nội dung</label>
                    <textarea name="abstract" rows="4" placeholder="Mô tả ngắn gọn nội dung bài..."></textarea>
                </div>

                <div class="input-group">
                    <label>File đính kèm (PDF, DOCX)</label>
                    <input type="file" name="paper" accept=".pdf,.doc,.docx" required style="background: var(--bg-tertiary); padding: 10px;">
                </div>

                <button type="submit" class="btn btn-primary">Gửi bài nộp</button>
                <a href="my_papers.php" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>