SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `fbu_seminar` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `fbu_seminar`;

-- 1. Bảng người dùng (Sinh viên, Admin, BTC)
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','organizer','admin') NOT NULL DEFAULT 'student',
  `student_code` varchar(20) DEFAULT NULL COMMENT 'Mã sinh viên',
  `class` varchar(50) DEFAULT NULL COMMENT 'Lớp',
  `faculty` varchar(100) DEFAULT NULL COMMENT 'Khoa',
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Bảng hội thảo
CREATE TABLE `seminars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `location` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `capacity` int(11) DEFAULT 100,
  `status` enum('upcoming','open','closed','completed') DEFAULT 'open',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Bảng đăng ký tham dự
CREATE TABLE `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `seminar_id` int(11) NOT NULL,
  `status` enum('confirmed','cancelled') DEFAULT 'confirmed',
  `registered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_reg` (`user_id`, `seminar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Bảng bài tham luận
CREATE TABLE `papers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `seminar_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `abstract` text,
  `file_path` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `rejection_reason` text,
  `presentation_status` enum('not_started','presented','absent') DEFAULT 'not_started',
  `presentation_time` datetime DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Bảng khách mời
CREATE TABLE `guests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seminar_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `organization` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dữ liệu mẫu
-- Mật khẩu là '123456'
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `student_code`, `class`, `faculty`) VALUES
(1, 'Admin System', 'admin@fbu.edu.vn', '$2y$10$YourHashedPasswordHere', 'admin', NULL, NULL, NULL),
(2, 'Nguyen Van SinhVien', 'student@fbu.edu.vn', '$2y$10$YourHashedPasswordHere', 'student', 'D124801', 'D12.48.02', 'CNTT'),
(3, 'Tran Thi BTC', 'btc@fbu.edu.vn', '$2y$10$YourHashedPasswordHere', 'organizer', NULL, NULL, NULL);

INSERT INTO `seminars` (`title`, `description`, `location`, `event_date`, `event_time`, `status`) VALUES
('Hội thảo: Ứng dụng AI trong Tài chính', 'Nội dung thảo luận về Fintech và AI...', 'Hội trường A', '2025-05-20', '09:00:00', 'open');

ALTER TABLE users ADD COLUMN reset_token VARCHAR(64) NULL;
ALTER TABLE users ADD COLUMN reset_expiry DATETIME NULL;

-- Xóa dữ liệu mẫu cũ nếu có (tránh trùng lặp email)
DELETE FROM users WHERE email IN ('admin@fbu.edu.vn', 'btc@fbu.edu.vn');

-- 1. Tạo tài khoản Admin
-- Tài khoản: admin@fbu.edu.vn | Mật khẩu: 123456
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`) VALUES
('Quản trị viên', 'admin@fbu.edu.vn', '$2y$10$TzY5vOMv.hFmfG7QvZqSnuW/xkBjQOqCxWgFpEmWxJYnGLrgXOi..', 'admin', 'active');

-- 2. Tạo tài khoản Ban tổ chức (Organizer)
-- Tài khoản: btc@fbu.edu.vn | Mật khẩu: 123456
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`) VALUES
('Ban Tổ Chức', 'btc@fbu.edu.vn', '$2y$10$TzY5vOMv.hFmfG7QvZqSnuW/xkBjQOqCxWgFpEmWxJYnGLrgXOi..', 'organizer', 'active');

-- Cập nhật mật khẩu là 123456 cho Admin và BTC
UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE email = 'admin@fbu.edu.vn';
UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE email = 'btc@fbu.edu.vn';