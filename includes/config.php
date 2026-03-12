<?php
session_start();

 $conn = new mysqli("localhost", "root", "", "fbu_seminar");
if ($conn->connect_error) die("Kết nối thất bại: " . $conn->connect_error);
 $conn->set_charset("utf8");

function base_url($path = '') {
    return "http://localhost/fbu_seminar/" . $path;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . base_url("index.php"));
        exit;
    }
}

function clean($str) {
    global $conn;
    return $conn->real_escape_string(htmlspecialchars($str));
}

function checkToast() {
    if(isset($_SESSION['toast'])) {
        $msg = $_SESSION['toast']['msg'];
        $type = $_SESSION['toast']['type'];
        echo "<script>document.addEventListener('DOMContentLoaded', function() { showToast('$msg', '$type'); });</script>";
        unset($_SESSION['toast']);
    }
}
?>