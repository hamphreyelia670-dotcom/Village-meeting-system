<?php

require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["chairman"]);


/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

// Upcoming meetings
$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM meetings
     WHERE Status = 'upcoming'"
);

$stmt->execute();

$result = $stmt->get_result();

$upcomingMeetings = $result->fetch_assoc()["total"];

$stmt->close();


// Total meetings
$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM meetings"
);

$stmt->execute();

$result = $stmt->get_result();

$totalMeetings = $result->fetch_assoc()["total"];

$stmt->close();


// Total announcements
$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM announcement"
);

$stmt->execute();

$result = $stmt->get_result();

$totalAnnouncements = $result->fetch_assoc()["total"];

$stmt->close();


// Pending resolutions
$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM resolutions
     WHERE Status IN ('pending', 'in_progress')"
);

$stmt->execute();

$result = $stmt->get_result();

$pendingResolutions = $result->fetch_assoc()["total"];

$stmt->close();


// Total citizens
$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'citizen'
     AND status = 'active'"
);

$stmt->execute();

$result = $stmt->get_result();

$totalCitizens = $result->fetch_assoc()["total"];

$stmt->close();


// Total SMS
$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM bulk_sms"
);

$stmt->execute();

$result = $stmt->get_result();

$totalSms = $result->fetch_assoc()["total"];

$stmt->close();


/*
|--------------------------------------------------------------------------
| Upcoming Meetings List
|--------------------------------------------------------------------------
*/

$meetings = $conn->query(
    "SELECT Meeting_id, Title, Meetingdate, Meetingtime, Location, Status
     FROM meetings
     WHERE Status IN ('upcoming', 'ongoing')
     ORDER BY Meetingdate ASC, Meetingtime ASC
     LIMIT 5"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Chairman Dashboard</title>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap');

        :root {
            --bg: #f7efe8;
            --surface: #ffffff;
            --surface-soft: #fff1e5;
            --sidebar: #71352f;
            --sidebar-soft: #a64b3c;
            --primary: #a64b3c;
            --primary-soft: #fae5d7;
            --accent: #c88b3a;
            --text: #30242a;
            --muted: #75666a;
            --line: #ead9ce;
            --shadow: 0 18px 45px rgba(79, 45, 38, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #f7efe8 0%, #fbf4ec 52%, #f2e4dd 100%);
            color: var(--text);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, var(--sidebar) 0%, #512824 100%);
            color: #fff4ec;
            padding: 26px 18px;
            overflow-y: auto;
            box-shadow: 12px 0 30px rgba(18, 60, 51, 0.12);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 10px 30px;
            font-size: 22px;
            font-weight: 800;
            font-family: 'Manrope', sans-serif;
            letter-spacing: -0.04em;
        }

        .logo-mark {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #e6b879;
            color: var(--sidebar);
            font-size: 12px;
            font-weight: 800;
        }

        .menu-title {
            margin: 16px 10px 10px;
            color: rgba(255, 229, 211, 0.72);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 12px;
            margin: 5px 0;
            border-radius: 10px;
            color: #f0d8ca;
            transition: 0.2s ease;
            font-weight: 500;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            transform: translateX(2px);
        }

        .logout {
            margin-top: 18px !important;
            background: rgba(233, 126, 92, 0.12) !important;
            color: #ffe0c2 !important;
        }

        .main {
            margin-left: 260px;
            padding: 34px 28px 42px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            margin-bottom: 28px;
        }

        .topbar h1 {
            margin: 0;
            font-size: clamp(28px, 3vw, 42px);
            line-height: 1.08;
            letter-spacing: -0.05em;
            font-family: 'Manrope', sans-serif;
        }

        .welcome {
            margin-top: 8px;
            color: var(--muted);
            font-size: 14px;
        }

        .profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 120px;
            min-height: 120px;
            padding: 18px 12px;
            border-radius: 24px;
            background: var(--surface);
            box-shadow: var(--shadow);
            border: 1px solid var(--line);
            text-align: center;
        }

        .avatar {
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--accent), #d89b52);
            color: white;
            font-weight: 700;
        }

        .profile strong {
            display: block;
            font-size: 14px;
        }

        .profile small {
            color: var(--muted);
            font-size: 11px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--surface);
            padding: 22px 20px;
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            right: -16px;
            bottom: -22px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(29, 91, 79, 0.06);
        }

        .stat-title {
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .stat-number {
            position: relative;
            z-index: 1;
            font-size: 32px;
            font-weight: 800;
            font-family: 'Manrope', sans-serif;
            letter-spacing: -0.05em;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(300px, 1fr);
            gap: 20px;
        }

        .card {
            background: var(--surface);
            padding: 22px;
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: var(--shadow);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .card-header h2 {
            margin: 0;
            font-size: 19px;
            font-family: 'Manrope', sans-serif;
            letter-spacing: -0.03em;
        }

        .view-link {
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
        }

        .meeting {
            padding: 16px 0;
            border-top: 1px solid var(--line);
        }

        .meeting:first-of-type {
            border-top: none;
            padding-top: 0;
        }

        .meeting-title {
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .meeting-info {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .badge {
            display: inline-block;
            margin-top: 8px;
            padding: 5px 10px;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .quick-actions {
            display: grid;
            gap: 10px;
        }

        .action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 15px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: var(--surface-soft);
            color: var(--text);
            font-weight: 600;
            transition: 0.2s ease;
        }

        .action:hover {
            background: #ebf3ee;
            border-color: rgba(29, 91, 79, 0.2);
            transform: translateX(2px);
        }

        .empty {
            text-align: center;
            color: var(--muted);
            padding: 30px 10px 10px;
        }

        @media (max-width: 1100px) {
            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 20px 14px;
            }

            .main {
                margin-left: 0;
                padding: 22px 16px 28px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile {
                width: 100%;
                min-height: auto;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

                gap: 15px;

                flex-direction: column;
            }

        }


        @media (max-width: 500px) {

            .stats {
                grid-template-columns: 1fr;
            }


            .main {
                padding: 15px;
            }


            .topbar h1 {
                font-size: 23px;
            }

        }

    </style>

</head>


<body>


<!-- SIDEBAR -->

<aside class="sidebar">

    <div class="logo">
        Village<span>Meeting</span>
    </div>


    <div class="menu-title">
        Main Menu
    </div>


    <a href="dashboard.php" class="active">
        🏠 Dashboard
    </a>

    <a href="meetings.php">
        📅 Meetings
    </a>

    <a href="agendas.php">
        📋 Agendas
    </a>

    <a href="attendance.php">
        👥 Attendance
    </a>

    <a href="announcements.php">
        📢 Announcements
    </a>

    <a href="minutes.php">
        📝 Minutes
    </a>

    <a href="resolutions.php">
        ✅ Resolutions
    </a>

    <div class="menu-title">
        Communication
    </div>

    <a href="sms.php">
        📱 Bulk SMS
    </a>

    <a href="feedback.php">
        💬 Feedback
    </a>

    <div class="menu-title">
        Account
    </div>

    <a href="profile.php">
        ⚙ Profile
    </a>

    <a href="../auth/logout.php" class="logout">
        🚪 Logout
    </a>

</aside>



<!-- MAIN -->

<main class="main">


    <!-- TOP BAR -->

    <div class="topbar">

        <div>

            <h1>Chairman Dashboard</h1>

            <div class="welcome">
                Welcome back,
                <?php echo htmlspecialchars($_SESSION["Firstname"]); ?>
            </div>

        </div>


        <div class="profile">
            <div class="avatar"><?php echo strtoupper(substr($_SESSION["Firstname"] ?? "C", 0, 1)); ?></div>
            <strong>
                <?php echo htmlspecialchars($_SESSION["Firstname"] ?? "Chairman"); ?>
            </strong>
            <small>Village Chairman</small>
        </div>

    </div>



    <!-- STATISTICS -->

    <section class="stats">


        <div class="stat-card">

            <div class="stat-title">
                Upcoming Meetings
            </div>

            <div class="stat-number">
                <?php echo $upcomingMeetings; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Total Meetings
            </div>

            <div class="stat-number">
                <?php echo $totalMeetings; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Announcements
            </div>

            <div class="stat-number">
                <?php echo $totalAnnouncements; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Pending Resolutions
            </div>

            <div class="stat-number">
                <?php echo $pendingResolutions; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Active Citizens
            </div>

            <div class="stat-number">
                <?php echo $totalCitizens; ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                SMS Sent
            </div>

            <div class="stat-number">
                <?php echo $totalSms; ?>
            </div>

        </div>

    </section>



    <section class="card" style="margin-bottom: 22px;">

        <div class="card-header">

            <h2>Chairman Responsibilities</h2>

        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; color: var(--text);">

            <span class="badge">Create meetings</span>
            <span class="badge">Update meetings</span>
            <span class="badge">Publish meetings</span>
            <span class="badge">Manage agendas</span>
            <span class="badge">Send notifications</span>
            <span class="badge">Send Bulk SMS</span>
            <span class="badge">Monitor attendance</span>
            <span class="badge">Manage resolutions</span>
            <span class="badge">View citizen feedback</span>
            <span class="badge">Publish outcomes</span>

        </div>

    </section>



    <!-- CONTENT -->

    <section class="content-grid">


        <!-- UPCOMING MEETINGS -->

        <div class="card">

            <div class="card-header">

                <h2>Upcoming Meetings</h2>

                <a
                    href="meetings.php"
                    class="view-link"
                >
                    View All
                </a>

            </div>


            <?php if ($meetings->num_rows > 0): ?>

                <?php while ($meeting = $meetings->fetch_assoc()): ?>

                    <div class="meeting">

                        <div class="meeting-title">

                            <?php
                            echo htmlspecialchars(
                                $meeting["Title"]
                            );
                            ?>

                        </div>


                        <div class="meeting-info">

                            📅
                            <?php
                            echo htmlspecialchars(
                                $meeting["Meetingdate"]
                            );
                            ?>

                            &nbsp;&nbsp;

                            🕐
                            <?php
                            echo htmlspecialchars(
                                $meeting["Meetingtime"]
                            );
                            ?>

                            <br>

                            📍
                            <?php
                            echo htmlspecialchars(
                                $meeting["Location"]
                            );
                            ?>

                            <br>

                            <span class="badge">

                                <?php
                                echo htmlspecialchars(
                                    $meeting["Status"]
                                );
                                ?>

                            </span>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="empty">
                    No upcoming meetings found.
                </div>

            <?php endif; ?>

        </div>



        <!-- QUICK ACTIONS -->

        <div class="card">

            <div class="card-header">

                <h2>Quick Actions</h2>

            </div>


            <div class="quick-actions">

                <a
                    href="../meetings/create.php"
                    class="action"
                >
                    <span>➕</span>
                    <span>Create Meeting</span>
                </a>

                <a
                    href="announcements.php"
                    class="action"
                >
                    <span>📢</span>
                    <span>Announcements</span>
                </a>

                <a
                    href="sms.php"
                    class="action"
                >
                    <span>📱</span>
                    <span>Bulk SMS</span>
                </a>

                <a
                    href="resolutions.php"
                    class="action"
                >
                    <span>✅</span>
                    <span>Manage Resolutions</span>
                </a>

                <a
                    href="minutes.php"
                    class="action"
                >
                    <span>📝</span>
                    <span>Manage Minutes</span>
                </a>

            </div>

        </div>

    </section>


</main>


</body>

</html>