<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["citizen"]);

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
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; }
        .wrap { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 8px 18px rgba(0,0,0,0.06); padding: 24px; }
        h1 { margin-bottom: 20px; }
        .item { border-top: 1px solid #eee; padding: 18px 0; }
        .meta { color: #666; font-size: 13px; margin-bottom: 8px; }
        .badge { display: inline-block; background: #eaf4ef; color: #1d5b4f; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: bold; margin-bottom: 8px; }
        a.back { display: inline-block; margin-bottom: 20px; color: #0a58ca; text-decoration: none; }
        body { background: #f7efe8; color: #30242a; }
        a.back { color: #a64b3c; }
        .item { border-top-color: #ead9ce; }
        .meta { color: #75666a; }
        .badge { background: #fff1d7; color: #8a5a2b; }
    </style>
</head>
<body>
<div class="wrap">
    <a class="back" href="dashboard.php">← Back to dashboard</a>
    <div class="card">
        <h1>Resolutions</h1>
        <?php if ($resolutions && $resolutions->num_rows > 0): ?>
            <?php while ($item = $resolutions->fetch_assoc()): ?>
                <div class="item">
                    <h3><?= htmlspecialchars($item['Title']) ?></h3>
                    <div class="badge"><?= htmlspecialchars($item['Status'] ?? 'pending') ?></div>
                    <div class="meta"><?= htmlspecialchars($item['MeetingTitle']) ?> • Deadline: <?= htmlspecialchars(date('d M Y', strtotime($item['Deadline']))) ?></div>
                    <p><?= htmlspecialchars($item['Description'] ?? 'No description provided.') ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No resolutions available yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
