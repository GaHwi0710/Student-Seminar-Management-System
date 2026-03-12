<?php 
require '../includes/config.php';
requireLogin();

 $user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Không tìm thấy hội thảo.'];
    header("Location: list_seminars.php");
    exit();
}

 $seminar_id = (int)$_GET['id'];

 $stmt = $conn->prepare("SELECT * FROM seminars WHERE id = ? AND status = 'open'");
 $stmt->bind_param("i", $seminar_id);
 $stmt->execute();
 $result = $stmt->get_result();
 $seminar = $result->fetch_assoc();

if (!$seminar) {
    $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Hội thảo không tồn tại hoặc đã đóng.'];
    header("Location: list_seminars.php");
    exit();
}

 $check_sql = "SELECT id FROM registrations WHERE user_id = ? AND seminar_id = ?";
 $check_stmt = $conn->prepare($check_sql);
 $check_stmt->bind_param("ii", $user_id, $seminar_id);
 $check_stmt->execute();
 $check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    $_SESSION['toast'] = ['type' => 'warning', 'msg' => 'Bạn đã đăng ký hội thảo này rồi.'];
    header("Location: list_seminars.php");
    exit();
}

 $count_sql = "SELECT COUNT(*) as total FROM registrations WHERE seminar_id = ?";
 $count_stmt = $conn->prepare($count_sql);
 $count_stmt->bind_param("i", $seminar_id);
 $count_stmt->execute();
 $current_regs = $count_stmt->get_result()->fetch_assoc()['total'];

if ($current_regs >= $seminar['capacity']) {
    $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Hội thảo đã hết chỗ.'];
    header("Location: list_seminars.php");
    exit();
}

 $insert_sql = "INSERT INTO registrations (user_id, seminar_id, registered_at) VALUES (?, ?, NOW())";
 $insert_stmt = $conn->prepare($insert_sql);
 $insert_stmt->bind_param("ii", $user_id, $seminar_id);

if ($insert_stmt->execute()) {
    $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Đăng ký thành công: ' . $seminar['title']];
    header("Location: my_registration.php");
} else {
    $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Có lỗi xảy ra, vui lòng thử lại.'];
    header("Location: list_seminars.php");
}
exit();
?>