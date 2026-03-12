<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FBU Seminar - <?php echo isset($pageTitle) ? $pageTitle : 'Hệ thống'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg: #0c1015;
            --bg-secondary: #141a22;
            --bg-tertiary: #1c242f;
            --fg: #e8edf4;
            --fg-muted: #6b7a8f;
            --accent: #d4a056;
            --accent-hover: #e5b367;
            --card: #151c25;
            --border: #252f3d;
            --success: #34d399;
            --danger: #f87171;
            --warning: #fbbf24;
            --info: #60a5fa;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--fg);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .bg-mesh {
            position: fixed; inset: 0; z-index: -1;
            background: radial-gradient(ellipse at 20% 30%, rgba(212, 160, 86, 0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 70%, rgba(96, 165, 250, 0.05) 0%, transparent 50%),
                        linear-gradient(180deg, var(--bg) 0%, var(--bg-secondary) 100%);
        }
        .glass-panel {
            background: rgba(21, 28, 37, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
        }

        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: 260px;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 50;
            transition: transform 0.3s ease;
        }
        .sidebar.collapsed { transform: translateX(-100%); }
        
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 20px; margin: 4px 12px; border-radius: 8px;
            color: var(--fg-muted); cursor: pointer; transition: all 0.2s;
            text-decoration: none;
        }
        .nav-item:hover { background: rgba(212, 160, 86, 0.1); color: var(--fg); }
        .nav-item.active { background: rgba(212, 160, 86, 0.15); color: var(--accent); }

        .main-content { margin-left: 260px; min-height: 100vh; transition: margin 0.3s; background: var(--bg); }
        .main-content.expanded { margin-left: 0; }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 20px; border-radius: 8px; font-weight: 500; cursor: pointer;
            border: none; transition: all 0.2s; font-size: 14px; text-decoration: none;
        }
        .btn-primary { background: var(--accent); color: #000; }
        .btn-primary:hover { background: var(--accent-hover); transform: translateY(-1px); }
        .btn-secondary { background: var(--bg-tertiary); color: var(--fg); border: 1px solid var(--border); }
        .btn-danger { background: rgba(248, 113, 113, 0.1); color: var(--danger); }
        
        .input-group { margin-bottom: 1rem; }
        .input-group label { display: block; font-size: 13px; color: var(--fg-muted); margin-bottom: 6px; }
        .input-group input, .input-group select, .input-group textarea {
            width: 100%; padding: 12px; background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; color: var(--fg); font-size: 14px;
        }
        .input-group input:focus { outline: none; border-color: var(--accent); }

        .badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-success { background: rgba(52, 211, 153, 0.15); color: var(--success); }
        .badge-warning { background: rgba(251, 191, 36, 0.15); color: var(--warning); }
        .badge-danger { background: rgba(248, 113, 113, 0.15); color: var(--danger); }
        .badge-info { background: rgba(96, 165, 250, 0.15); color: var(--info); }

        .table-container { overflow-x: auto; border: 1px solid var(--border); border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: var(--bg-tertiary); padding: 12px 16px; text-align: left; font-size: 12px; color: var(--fg-muted); text-transform: uppercase; }
        td { padding: 14px 16px; border-top: 1px solid var(--border); font-size: 14px; }
        tr:hover td { background: rgba(212, 160, 86, 0.03); }

        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 200; display: flex; flex-direction: column; gap: 10px; }
        .toast {
            background: var(--bg-secondary); border: 1px solid var(--border);
            padding: 12px 16px; border-radius: 8px; min-width: 280px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3); display: flex; align-items: center; gap: 10px;
            animation: toastIn 0.3s ease forwards;
        }
        .toast.success { border-left: 4px solid var(--success); }
        .toast.error { border-left: 4px solid var(--danger); }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
        @keyframes toastIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes toastOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
    </style>
</head>
<body>
    <div class="bg-mesh"></div>
    <div id="toast-container" class="toast-container"></div>