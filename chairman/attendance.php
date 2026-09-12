<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["chairman"]);

$attendance = $conn->query(
    "SELECT a.AttendanceID, a.Attendancestatus, a.Date, m.Title AS MeetingTitle, u.Firstname, u.Lastname
     FROM attendance a
     INNER JOIN meetings m ON m.Meeting_id = a.MeetingId
     INNER JOIN users u ON u.userId = a.UserId
     ORDER BY a.Date DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f3f7fa; }
        .wrap { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 16px; box-shadow: 0 12px 28px rgba(15,23,42,0.08); padding: 24px; }
        h1 { margin-top: 0; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .topbar a { background: #a64b3c; color: white; text-decoration: none; padding: 10px 18px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; }
        th { background: #f8fafc; }
        body { background: #f7efe8; color: #30242a; }
        .topbar a { background: #a64b3c; }
        th, td { border-bottom-color: #ead9ce; }
        th { background: #fff1e5; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="topbar">
        <h1>Attendance Records</h1>
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
    <div class="card">
        <?php if ($attendance && $attendance->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Meeting</th>
                        <th>Citizen</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $attendance->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['MeetingTitle']) ?></td>
                            <td><?= htmlspecialchars($row['Firstname'] . ' ' . $row['Lastname']) ?></td>
                            <td><?= htmlspecialchars($row['Attendancestatus']) ?></td>
                            <td><?= htmlspecialchars(date('d M Y', strtotime($row['Date']))) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No attendance records found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
