<?php

require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["admin"]);

function getCount(mysqli $conn, string $sql): int
{
    $result = $conn->query($sql);

    if (!$result) {
        die("Unable to load dashboard statistics: " . $conn->error);
    }

    return (int) $result->fetch_assoc()["total"];
}

$totalUsers = getCount($conn, "SELECT COUNT(*) AS total FROM users");
$activeCitizens = getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM users WHERE role = 'citizen' AND status = 'active'"
);
$upcomingMeetings = getCount(
    $conn,
    "SELECT COUNT(*) AS total FROM meetings WHERE Status IN ('upcoming', 'ongoing')"
);
$totalAnnouncements = getCount($conn, "SELECT COUNT(*) AS total FROM announcement");

$meetings = $conn->query(
    "SELECT Title, Meetingdate, Meetingtime, Location, Status
     FROM meetings
     WHERE Status IN ('upcoming', 'ongoing')
     ORDER BY Meetingdate ASC, Meetingtime ASC
     LIMIT 4"
);

if (!$meetings) {
    die("Unable to load upcoming meetings: " . $conn->error);
}

$firstName = htmlspecialchars($_SESSION["Firstname"] ?? "Admin");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Village Meeting System</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap');

        :root {
            --ink: #30242a;
            --muted: #75666a;
            --cream: #f7efe8;
            --paper: #fffaf5;
            --accent: #a64b3c;
            --accent-dark: #71352f;
            --gold: #c88b3a;
            --line: #ead9ce;
            --shadow: 0 18px 45px rgba(79, 45, 38, .1);
        }

        * { box-sizing: border-box; }
        body { margin: 0; color: var(--ink); background: linear-gradient(135deg, #f7efe8 0%, #fbf4ec 55%, #f2e4dd 100%); font-family: 'DM Sans', sans-serif; }
        a { color: inherit; text-decoration: none; }
        .layout { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; flex: 0 0 250px; padding: 28px 18px; color: #fff4ec; background: var(--accent-dark); }
        .brand { display: flex; align-items: center; gap: 11px; padding: 0 12px 38px; color: #fff; font: 800 18px Manrope, sans-serif; }
        .brand-mark { display: grid; place-items: center; width: 34px; height: 34px; color: var(--accent-dark); background: #e6b879; border-radius: 10px; font-size: 12px; }
        .nav-label { padding: 0 12px 12px; color: #d9ae9a; font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; }
        .nav-link { display: flex; align-items: center; gap: 12px; margin: 4px 0; padding: 12px; color: #f0d8ca; border-radius: 9px; font-size: 14px; transition: .2s ease; }
        .nav-link:hover, .nav-link.active { color: #fff; background: rgba(255,255,255,.12); }
        .nav-icon { width: 20px; color: #e6b879; text-align: center; font-weight: 700; }
        .logout { margin-top: 34px; color: #f8c1ae; }
        .main { width: 100%; min-width: 0; padding: 34px clamp(22px, 4vw, 58px); }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 38px; }
        .eyebrow { margin: 0 0 8px; color: var(--coral); font-size: 11px; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; }
        h1, h2, h3 { font-family: Manrope, sans-serif; }
        h1 { margin: 0; font-size: clamp(28px, 3vw, 40px); letter-spacing: -1.5px; }
        .welcome { margin: 8px 0 0; color: var(--muted); font-size: 14px; }
        .profile { display: flex; align-items: center; gap: 11px; padding: 8px 12px 8px 8px; background: var(--paper); border: 1px solid var(--line); border-radius: 30px; box-shadow: 0 8px 25px rgba(24,51,47,.04); }
        .avatar { display: grid; place-items: center; width: 34px; height: 34px; color: #fff; background: var(--coral); border-radius: 50%; font-weight: 700; }
        .profile strong { font-size: 13px; }
        .profile small { display: block; margin-top: 2px; color: var(--muted); font-size: 11px; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
        .stat-card, .panel { background: var(--paper); border: 1px solid var(--line); border-radius: 14px; box-shadow: var(--shadow); }
        .stat-card { position: relative; padding: 22px; overflow: hidden; }
        .stat-card::after { position: absolute; right: -22px; bottom: -34px; width: 94px; height: 94px; content: ""; background: #f7e5d3; border-radius: 50%; }
        .stat-label { color: var(--muted); font-size: 12px; font-weight: 600; }
        .stat-value { display: block; margin-top: 12px; font: 800 30px Manrope, sans-serif; }
        .stat-note { display: block; margin-top: 7px; color: var(--accent); font-size: 11px; font-weight: 700; }
        .dashboard-grid { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(270px, .65fr); gap: 20px; }
        .panel { padding: 24px; }
        .panel-heading { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 20px; }
        .panel-heading h2 { margin: 0; font-size: 19px; letter-spacing: -.5px; }
        .panel-link { color: var(--accent); font-size: 12px; font-weight: 700; }
        .meeting { display: flex; align-items: center; gap: 14px; padding: 15px 0; border-top: 1px solid var(--line); }
        .meeting:first-of-type { padding-top: 0; border-top: 0; }
        .date { display: grid; place-items: center; width: 49px; height: 49px; flex: 0 0 49px; color: #fff; background: var(--accent); border-radius: 10px; font-weight: 700; line-height: 1.1; text-align: center; }
        .date strong { display: block; font-size: 18px; }
        .date small { font-size: 9px; text-transform: uppercase; }
        .meeting h3 { margin: 0 0 5px; font-size: 14px; }
        .meeting p { margin: 0; color: var(--muted); font-size: 12px; }
        .status { margin-left: auto; padding: 5px 8px; color: #7d3c2f; background: #fae5d7; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: capitalize; }
        .empty { padding: 15px 0 4px; color: var(--muted); font-size: 13px; }
        .actions { display: grid; gap: 10px; }
        .action { display: flex; justify-content: space-between; align-items: center; padding: 14px 15px; border: 1px solid var(--line); border-radius: 9px; font-size: 13px; font-weight: 600; transition: .2s ease; }
        .action:hover { border-color: #d6a88f; background: #fff2e8; }
        .action span { color: var(--coral); font-size: 17px; }
        .mobile-toggle { display: none; padding: 8px 11px; color: var(--accent); background: var(--paper); border: 1px solid var(--line); border-radius: 8px; font-size: 18px; }
        @media (max-width: 950px) { .stats { grid-template-columns: repeat(2, 1fr); } .dashboard-grid { grid-template-columns: 1fr; } }
        @media (max-width: 700px) {
            .layout { display: block; }
            .sidebar { width: 100%; padding: 18px; }
            .brand { padding-bottom: 18px; }
            .nav { display: none; }
            .nav.open { display: block; }
            .mobile-toggle { display: block; }
            .topbar { align-items: flex-start; }
            .profile { display: none; }
            .main { padding: 26px 18px; }
        }
        @media (max-width: 450px) { .stats { grid-template-columns: 1fr; } .meeting { align-items: flex-start; } .status { display: none; } }
    </style>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand"><span class="brand-mark">VM</span> Village Meeting</div>
            <button class="mobile-toggle" type="button" aria-label="Toggle navigation" onclick="document.querySelector('.nav').classList.toggle('open')">Menu</button>
            <nav class="nav">
                <div class="nav-label">Workspace</div>
                <a class="nav-link active" href="dashboard.php"><span class="nav-icon">+</span> Dashboard</a>
                <a class="nav-link" href="users.php"><span class="nav-icon">@</span> Users</a>
                <a class="nav-link" href="../index.php#mikutano"><span class="nav-icon">#</span> Meetings</a>
                <a class="nav-link" href="users.php"><span class="nav-icon">!</span> User accounts</a>
                <a class="nav-link" href="adduser.php"><span class="nav-icon">+</span> Add staff</a>
                <a class="nav-link" href="../index.php#kuhusu"><span class="nav-icon">%</span> System information</a>
                <a class="nav-link logout" href="../auth/logout.php"><span class="nav-icon">←</span> Log out</a>
            </nav>
        </aside>

        <main class="main">
            <header class="topbar">
                <div>
                    <p class="eyebrow">Administrator overview</p>
                    <h1>Good morning, <?php echo $firstName; ?></h1>
                    <p class="welcome">Here is what is happening in your village system today.</p>
                </div>
                <div class="profile">
                    <span class="avatar"><?php echo strtoupper(substr($_SESSION["Firstname"] ?? "A", 0, 1)); ?></span>
                    <div><strong><?php echo $firstName; ?></strong><small>Administrator</small></div>
                </div>
            </header>

            <section class="stats" aria-label="Dashboard statistics">
                <article class="stat-card"><span class="stat-label">Total users</span><strong class="stat-value"><?php echo $totalUsers; ?></strong><span class="stat-note">All accounts</span></article>
                <article class="stat-card"><span class="stat-label">Active citizens</span><strong class="stat-value"><?php echo $activeCitizens; ?></strong><span class="stat-note">Currently active</span></article>
                <article class="stat-card"><span class="stat-label">Upcoming meetings</span><strong class="stat-value"><?php echo $upcomingMeetings; ?></strong><span class="stat-note">Upcoming & ongoing</span></article>
                <article class="stat-card"><span class="stat-label">Announcements</span><strong class="stat-value"><?php echo $totalAnnouncements; ?></strong><span class="stat-note">Published updates</span></article>
            </section>

            <section class="dashboard-grid">
                <div class="panel">
                    <div class="panel-heading"><h2>Upcoming meetings</h2><a class="panel-link" href="../index.php#mikutano">View all</a></div>
                    <?php if ($meetings->num_rows > 0): ?>
                        <?php while ($meeting = $meetings->fetch_assoc()): ?>
                            <div class="meeting">
                                <div class="date"><strong><?php echo date("d", strtotime($meeting["Meetingdate"])); ?></strong><small><?php echo date("M", strtotime($meeting["Meetingdate"])); ?></small></div>
                                <div><h3><?php echo htmlspecialchars($meeting["Title"]); ?></h3><p><?php echo htmlspecialchars($meeting["Location"]); ?> · <?php echo date("g:i A", strtotime($meeting["Meetingtime"])); ?></p></div>
                                <span class="status"><?php echo htmlspecialchars($meeting["Status"]); ?></span>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="empty">No upcoming meetings have been scheduled.</p>
                    <?php endif; ?>
                </div>

                <div class="panel">
                    <div class="panel-heading"><h2>Quick actions</h2></div>
                    <div class="actions">
                        <a class="action" href="users.php">Manage users <span>→</span></a>
                        <a class="action" href="adduser.php">Add staff member <span>+</span></a>
                        <a class="action" href="../index.php#mikutano">View public information <span>→</span></a>
                        <a class="action" href="../index.php#kuhusu">System information <span>→</span></a>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
