<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
 $base_url = "http://localhost/fbu_seminar/";

if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['user_role'];
    
    switch ($role) {
        case 'admin':
            header("Location: " . $base_url . "admin/dashboard.php");
            break;
        case 'organizer':
            header("Location: " . $base_url . "organizer/dashboard.php");
            break;
        default: 
            header("Location: " . $base_url . "student/dashboard.php");
            break;
    }
} else {
    header("Location: " . $base_url . "login.php");
}
exit(); 
?>