<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["citizen"]);

$minutes = $conn->query(
    "SELECT m.MinutesId, m.Content, m.Createdat, mt.Title AS MeetingTitle
     FROM minutes m
     INNER JOIN meetings mt ON mt.Meeting_id = m.MeetingId
     WHERE m.Published = 1
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
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; }
        .wrap { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 8px 18px rgba(0,0,0,0.06); padding: 24px; }
        h1 { margin-bottom: 20px; }
        .item { border-top: 1px solid #eee; padding: 18px 0; }
        .date { color: #666; font-size: 13px; margin-top: 6px; }
        a.back { display: inline-block; margin-bottom: 20px; color: #0a58ca; text-decoration: none; }
        body { background: #f7efe8; color: #30242a; }
        a.back { color: #a64b3c; }
        .item { border-top-color: #ead9ce; }
        .date { color: #75666a; }
    </style>
</head>
<body>
<div class="wrap">
    <a class="back" href="dashboard.php">← Back to dashboard</a>
    <div class="card">
        <h1>Meeting Minutes</h1>
        <?php if ($minutes && $minutes->num_rows > 0): ?>
            <?php while ($item = $minutes->fetch_assoc()): ?>
                <div class="item">
                    <h3><?= htmlspecialchars($item['MeetingTitle']) ?></h3>
                    <div class="date"><?= htmlspecialchars(date('d M Y', strtotime($item['Createdat']))) ?></div>
                    <p><?= nl2br(htmlspecialchars($item['Content'] ?? 'No minute content available.')) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No published minutes available yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
