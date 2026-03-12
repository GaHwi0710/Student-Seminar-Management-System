<?php
// File này tự tạo mật khẩu mới trên server 

// 1. Kết nối DB (Sửa thông tin nếu khác)
 $conn = new mysqli("localhost", "root", "", "fbu_seminar");

// 2. Tạo hash cho mật khẩu 123456
 $new_pass = '123456';
 $hash = password_hash($new_pass, PASSWORD_DEFAULT);

 $conn->query("UPDATE users SET password = '$hash' WHERE email = 'admin@fbu.edu.vn'");
 $conn->query("UPDATE users SET password = '$hash' WHERE email = 'btc@fbu.edu.vn'");

echo "Đã cập nhật mật khẩu thành công! <br>";
echo "Hash tạo ra là: " . $hash . "<br>";
echo "<a href='index.php'>Về trang đăng nhập</a>";
?>