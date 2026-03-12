<?php
 $conn = new mysqli("localhost", "root", "", "fbu_seminar"); 

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

 $password_plain = '123456';
 $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

 $conn->query("DELETE FROM users WHERE email IN ('admin@fbu.edu.vn', 'btc@fbu.edu.vn')");

 $sql = "INSERT INTO users (name, email, password, role, status) VALUES 
('Quản trị viên', 'admin@fbu.edu.vn', '$password_hash', 'admin', 'active'),
('Ban Tổ Chức', 'btc@fbu.edu.vn', '$password_hash', 'organizer', 'active')";

if ($conn->query($sql) === TRUE) {
    echo "<h1 style='color:green'>Tạo tài khoản thành công!</h1>";
    echo "<p><strong>Email Admin:</strong> admin@fbu.edu.vn</p>";
    echo "<p><strong>Email BTC:</strong> btc@fbu.edu.vn</p>";
    echo "<p><strong>Mật khẩu chung:</strong> 123456</p>";
    echo "<hr><p style='color:red'>Hãy xóa file này ngay lập tức để bảo mật!</p>";
    
} else {
    echo "Lỗi: " . $conn->error;
}

 $conn->close();
?>