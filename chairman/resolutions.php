<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["chairman"]);

$resolutions = $conn->query(
    "SELECT r.ResolutionId, r.Title, r.Description, r.Status, r.Deadline, m.Title AS MeetingTitle
     FROM resolutions r
     INNER JOIN meetings m ON m.Meeting_id = r.MeetingId
     ORDER BY r.Createdat DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolutions</title>
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
        .badge { display: inline-block; background: #ecfdf5; color: #166534; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: bold; margin-bottom: 8px; }
        body { background: #f7efe8; color: #30242a; }
        .topbar a { background: #a64b3c; }
        .row { border-top-color: #ead9ce; }
        .meta { color: #75666a; }
        .badge { background: #fff1d7; color: #8a5a2b; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="topbar">
        <h1>Resolutions</h1>
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
    <div class="card">
        <?php if ($resolutions && $resolutions->num_rows > 0): ?>
            <?php while ($item = $resolutions->fetch_assoc()): ?>
                <div class="row">
                    <h3><?= htmlspecialchars($item['Title']) ?></h3>
                    <div class="badge"><?= htmlspecialchars($item['Status']) ?></div>
                    <div class="meta"><?= htmlspecialchars($item['MeetingTitle']) ?> • Deadline: <?= htmlspecialchars(date('d M Y', strtotime($item['Deadline']))) ?></div>
                    <p><?= htmlspecialchars($item['Description'] ?? 'No description provided.') ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No resolutions available.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
