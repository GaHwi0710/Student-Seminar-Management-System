<?php
 $role = $_SESSION['user_role'] ?? 'guest';
 $name = $_SESSION['user_name'] ?? 'Guest';
 $avatar = strtoupper(substr($name, 0, 2));
 $current_script = $_SERVER['SCRIPT_NAME'];

 $menu = [
    'student' => [
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>', 'label' => 'Tổng quan', 'link' => 'student/dashboard.php'],
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>', 'label' => 'Danh sách hội thảo', 'link' => 'student/seminars.php'],
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>', 'label' => 'Đăng ký của tôi', 'link' => 'student/my_registrations.php'],
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>', 'label' => 'Bài tham luận', 'link' => 'student/my_papers.php'],
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>', 'label' => 'Thông tin cá nhân', 'link' => 'student/profile.php'],
    ],
    'admin' => [
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>', 'label' => 'Tổng quan', 'link' => 'admin/dashboard.php'],
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>', 'label' => 'Quản lý người dùng', 'link' => 'admin/manage_students.php'],
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>', 'label' => 'Báo cáo thống kê', 'link' => 'admin/reports.php'],
    ],
    'organizer' => [
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>', 'label' => 'Tổng quan', 'link' => 'organizer/dashboard.php'],
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>', 'label' => 'Quản lý hội thảo', 'link' => 'organizer/manage_seminars.php'],
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>', 'label' => 'Xét duyệt bài', 'link' => 'organizer/manage_papers.php'],
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>', 'label' => 'Người đăng ký', 'link' => 'organizer/manage_regs.php'],
        ['icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>', 'label' => 'Quản lý khách mời', 'link' => 'organizer/manage_guests.php'],
    ]
];
?>

<aside class="sidebar" id="sidebar">
    <a href="<?php echo base_url('index.php'); ?>" style="text-decoration: none; color: inherit; padding: 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px;">
        <div style="width: 40px; height: 40px; border-radius: 10px; overflow: hidden; flex-shrink: 0; background: #fff;">
            <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div>
            <div style="font-weight: 700;">FBU Seminar</div>
            <div style="font-size: 11px; color: var(--fg-muted); text-transform: uppercase;"><?php echo $role; ?></div>
        </div>
    </a>
    
    <nav style="flex: 1; padding: 15px 0; overflow-y: auto;">
        <?php foreach ($menu[$role] ?? [] as $item): 
            $active = (strpos($current_script, $item['link']) !== false) ? 'active' : '';
        ?>
            <a href="<?php echo base_url($item['link']); ?>" class="nav-item <?php echo $active; ?>">
                <?php echo $item['icon']; ?><span><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div style="padding: 15px; border-top: 1px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; font-weight: 600;"><?php echo $avatar; ?></div>
            <div style="flex: 1;">
                <div style="font-size: 13px; font-weight: 600;"><?php echo $name; ?></div>
            </div>
            <a href="<?php echo base_url('logout.php'); ?>" class="btn btn-icon" style="padding: 8px; background: transparent;" title="Đăng xuất">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--fg-muted)" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            </a>
        </div>
    </div>
</aside>