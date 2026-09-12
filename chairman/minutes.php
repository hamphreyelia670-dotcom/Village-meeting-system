<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["chairman"]);

$minutes = $conn->query(
    "SELECT m.MinutesId, m.Content, m.Createdat, mt.Title AS MeetingTitle
     FROM minutes m
     INNER JOIN meetings mt ON mt.Meeting_id = m.MeetingId
     ORDER BY m.Createdat DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minutes</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f3f7fa; }
        .wrap { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 16px; box-shadow: 0 12px 28px rgba(15,23,42,0.08); padding: 24px; }
        h1 { margin-top: 0; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .topbar a { background: #a64b3c; color: white; text-decoration: none; padding: 10px 18px; border-radius: 10px; }
        .row { padding: 18px 0; border-top: 1px solid #e5e7eb; }
        .row h3 { margin: 0 0 8px; }
        .meta { color: #54616d; font-size: 14px; margin-bottom: 8px; }
        body { background: #f7efe8; color: #30242a; }
        .topbar a { background: #a64b3c; }
        .row { border-top-color: #ead9ce; }
        .meta { color: #75666a; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="topbar">
        <h1>Meeting Minutes</h1>
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
    <div class="card">
        <?php if ($minutes && $minutes->num_rows > 0): ?>
            <?php while ($item = $minutes->fetch_assoc()): ?>
                <div class="row">
                    <h3><?= htmlspecialchars($item['MeetingTitle']) ?></h3>
                    <div class="meta"><?= htmlspecialchars(date('d M Y', strtotime($item['Createdat']))) ?></div>
                    <p><?= nl2br(htmlspecialchars($item['Content'] ?? 'No content provided.')) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No minutes available.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
