# FBU SEMINAR - Hệ Thống Quản Lý Hội Thảo

![FBU Seminar Logo](assets/img/logo.png) 

## 1. Giới thiệu
**FBU Seminar** là hệ thống quản lý hội thảo trực tuyến được xây dựng bằng ngôn ngữ PHP thuần (Native PHP) và MySQL. Hệ thống cung cấp giải pháp toàn diện để quản lý người dùng, tổ chức hội thảo, đăng ký tham dự và nộp bài tham luận.

## 2. Tính năng chính

Hệ thống được phân chia thành 3 vai trò người dùng chính với các chức năng riêng biệt:

### 👨‍💼 Quản trị viên (Admin)
*   **Tổng quan:** Thống kê số lượng người dùng, hội thảo.
*   **Quản lý Người dùng:**
    *   Thêm, Sửa, Xóa tài khoản Sinh viên.
    *   Thêm, Sửa, Xóa tài khoản Ban Tổ Chức (BTC).
*   **Báo cáo thống kê:** Xem biểu đồ đăng ký theo hội thảo và xuất file CSV.

### 🧑‍💻 Ban Tổ Chức (Organizer)
*   **Quản lý Hội thảo:** Tạo mới, chỉnh sửa thông tin hội thảo (tiêu đề, địa điểm, thời gian, sức chứa).
*   **Xét duyệt bài tham luận:** Duyệt hoặc từ chối bài nộp của sinh viên kèm lý do.
*   **Quản lý Khách mời:** Thêm thông tin diễn giả/khách mời cho từng hội thảo.
*   **Danh sách đăng ký:** Xem danh sách sinh viên đã đăng ký, hủy đăng ký.
*   **Lịch trình báo cáo:** Cập nhật trạng thái trình bày (Đã trình bày/Vắng mặt).

### 👨‍🎓 Sinh viên (Student)
*   **Đăng ký hội thảo:** Xem danh sách hội thảo đang mở và đăng ký tham dự.
*   **Nộp bài tham luận:** Upload file bài làm (PDF/DOCX) cho hội thảo đã đăng ký.
*   **Quản lý cá nhân:** Xem danh sách đã đăng ký, xem trạng thái bài nộp, cập nhật thông tin cá nhân.

## 3. Công nghệ sử dụng

*   **Back-end:** PHP 7.x / 8.x (Native).
*   **Database:** MySQL / MariaDB.
*   **Front-end:** HTML5, CSS3, JavaScript.
*   **CSS Framework:** Tailwind CSS (qua CDN) & Custom CSS (Glassmorphism UI).
*   **Font:** Inter & Playfair Display (Google Fonts).

## 4. Hướng dẫn cài đặt

### Yêu cầu môi trường
*   Web Server: Apache (XAMPP, WAMP, Laragon...).
*   PHP phiên bản 7.0 trở lên.
*   MySQL Database.

### Các bước cài đặt

1.  **Clone hoặc Copy source code:**
    Đặt thư mục dự án vào thư mục web server (ví dụ: `htdocs` hoặc `www`).
    ```bash
    Đường dẫn ví dụ: C:\xampp\htdocs\fbu_seminar
    ```

2.  **Tạo cơ sở dữ liệu:**
    *   Mở trình duyệt, truy cập `http://localhost/phpmyadmin`.
    *   Tạo database mới tên là `fbu_seminar`.
    *   Chọn tab "Import" và chọn file `database.sql` (nằm trong thư mục gốc) để nhập dữ liệu.

3.  **Cấu hình kết nối:**
    Mở file `includes/config.php` và chỉnh sửa thông tin kết nối DB nếu khác mặc định:
    ```php
    $conn = new mysqli("localhost", "root", "", "fbu_seminar");
    ```
    Chỉnh sửa đường dẫn gốc trong hàm `base_url`:
    ```php
    function base_url($path = '') {
        return "http://localhost/fbu_seminar/" . $path; 
    }
    ```

4.  **Cấp quyền ghi cho thư mục uploads:**
    Đảm bảo thư mục `uploads/` và `uploads/papers/` có quyền ghi (writable) để lưu file bài nộp.

5.  **Truy cập hệ thống:**
    Mở trình duyệt và truy cập: `http://localhost/fbu_seminar/`

## 5. Tài khoản mặc định

Sau khi import file `database.sql`, bạn có thể sử dụng các tài khoản sau để đăng nhập (Mật khẩu mặc định: **123456**):

| Vai trò | Email | Mật khẩu |
| :--- | :--- | :--- |
| **Admin** | `admin@fbu.edu.vn` | 123456 |
| **Ban Tổ Chức** | `btc@fbu.edu.vn` | 123456 |
| **Sinh viên** | `student@fbu.edu.vn` | 123456 |

## 6. Cấu trúc thư mục

```
fbu_seminar/
├── admin/                 # Các chức năng của Admin
│   ├── dashboard.php
│   ├── edit_user.php
│   ├── manage_organizers.php
│   ├── manage_students.php
│   └── reports.php
├── assets/                # Tài nguyên tĩnh (CSS, JS, Images)
│   ├── css/
│   ├── img/
│   └── js/
├── includes/              # Các file chung của hệ thống
│   ├── config.php         # Cấu hình DB và hàm helper
│   ├── footer.php
│   ├── header.php         # Chứa CSS chung
│   └── sidebar.php        # Menu điều hướng
├── organizer/             # Các chức năng của Ban Tổ Chức
│   ├── dashboard.php
│   ├── edit_seminar.php
│   ├── manage_guests.php
│   ├── manage_papers.php
│   ├── manage_regs.php
│   ├── manage_seminars.php
│   └── presentation_status.php
├── student/               # Các chức năng của Sinh viên
│   ├── dashboard.php
│   ├── my_papers.php
│   ├── my_registrations.php
│   ├── profile.php
│   ├── register_event.php
│   ├── seminars.php
│   └── submit_paper.php
├── uploads/               # Nơi lưu file upload (Bài tham luận)
├── database.sql           # File tạo Database
├── index.php              # Trang điều hướng/Đăng nhập
├── logout.php             # Đăng xuất
└── README.md              # File hướng dẫn này
```

## 7. Tác giả
*   **Tên dự án:** FBU Seminar Management System.
*   **Phát triển bởi:** Kiều Gia Huy
```