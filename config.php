<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fbu_seminar');

 $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
 $conn->set_charset("utf8mb4");

function base_url($path = '') {
    return "http://localhost/fbu_seminar/" . $path;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function checkRole($role) {
    if (!isLoggedIn() || $_SESSION['user_role'] != $role) {
        header("Location: " . base_url("login.php"));
        exit();
    }
}

function clean($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($data));
}

function showAlert($msg, $type = 'success') {
    if ($msg) {
        echo "<div class='alert alert-$type'>$msg</div>";
    }
}
?>