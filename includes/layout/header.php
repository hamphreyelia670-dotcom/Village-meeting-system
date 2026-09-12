<?php
if (!isset($pageTitle)) {
    $pageTitle = APP_NAME;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #30242a;
            --muted: #75666a;
            --cream: #f7efe8;
            --paper: #ffffff;
            --accent: #a64b3c;
            --accent-dark: #71352f;
            --accent-soft: #fae5d7;
            --line: #ead9ce;
            --gold: #c88b3a;
            --shadow: 0 18px 45px rgba(79, 45, 38, 0.1);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #f7efe8 0%, #fbf4ec 50%, #f2e4dd 100%);
            color: var(--ink);
        }
        a { color: inherit; text-decoration: none; }
        .container { max-width: 1180px; margin: 0 auto; padding: 0 24px; }
        .site-header {
            border-bottom: 1px solid var(--line);
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(12px);
        }
        .site-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 80px;
            gap: 24px;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-family: 'Manrope', sans-serif;
            letter-spacing: -0.03em;
        }
        .brand-mark {
            display: inline-grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--accent);
            color: white;
            font-size: 12px;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 22px;
            color: var(--muted);
            font-size: 14px;
        }
        .nav-button, .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 18px;
            border-radius: 10px;
            background: var(--accent);
            color: white;
            font-weight: 700;
            box-shadow: 0 10px 22px rgba(29, 91, 79, 0.14);
        }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 18px;
            border-radius: 10px;
            background: var(--accent-soft);
            color: var(--accent-dark);
            border: 1px solid rgba(29,91,79,0.1);
            font-weight: 700;
        }
        .page-shell {
            max-width: 1200px;
            margin: 32px auto 48px;
            padding: 0 24px;
        }
        .card {
            background: rgba(255,255,255,0.9);
            border: 1px solid var(--line);
            border-radius: 22px;
            box-shadow: var(--shadow);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .badge-success { background: #eaf9f1; color: #1d7a4f; }
        .badge-warning { background: #fff3de; color: #a56b08; }
        .badge-info { background: #eaf1ff; color: #2b5ec7; }
        .badge-primary { background: var(--accent-soft); color: var(--accent); }
        .badge-secondary { background: #eef2f4; color: #455a5a; }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container site-nav">
            <a href="<?= app_url('index.php') ?>" class="brand">
                <span class="brand-mark">VM</span>
                <span>Village Meeting</span>
            </a>
            <nav class="nav-links" aria-label="Main navigation">
                <a href="<?= app_url('index.php') ?>">Home</a>
                <a href="<?= app_url('auth/login.php') ?>">Login</a>
                <a href="<?= app_url('auth/register.php') ?>">Register</a>
            </nav>
        </div>
    </header>
    <main class="page-shell">
