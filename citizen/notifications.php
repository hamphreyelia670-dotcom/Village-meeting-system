<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["citizen"]);

$notifications = $conn->query(
    "SELECT AnnouncementId AS id, Title AS title, Message AS message, Createdat AS created_at, 'announcement' AS type
     FROM announcement
     WHERE Published = 1
     UNION ALL
     SELECT FeedbackId AS id, Subject AS title, Message AS message, createdat AS created_at, 'feedback' AS type
     FROM feedback
     WHERE UserId = {$_SESSION['userId']}
     ORDER BY created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; }
        .wrap { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 8px 18px rgba(0,0,0,0.06); padding: 24px; }
        h1 { margin-bottom: 20px; }
        .item { border-top: 1px solid #eee; padding: 18px 0; }
        .type { display: inline-block; background: #f1e4dc; color: #71352f; padding: 5px 10px; border-radius: 999px; font-size: 11px; font-weight: bold; }
        .date { color: #666; font-size: 13px; margin: 7px 0; }
        a.back { display: inline-block; margin-bottom: 20px; color: #0a58ca; text-decoration: none; }
        body { background: #f7efe8; color: #30242a; }
        a.back { color: #a64b3c; }
        .item { border-top-color: #ead9ce; }
        .type { background: #f1e4dc; color: #71352f; }
        .date { color: #75666a; }
    </style>
</head>
<body>
<div class="wrap">
    <a class="back" href="dashboard.php">← Back to dashboard</a>
    <div class="card">
        <h1>Notifications</h1>
        <?php if ($notifications && $notifications->num_rows > 0): ?>
            <?php while ($item = $notifications->fetch_assoc()): ?>
                <div class="item">
                    <span class="type"><?= htmlspecialchars(strtoupper($item['type'])) ?></span>
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <div class="date"><?= htmlspecialchars(date('d M Y', strtotime($item['created_at']))) ?></div>
                    <p><?= htmlspecialchars($item['message']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No notifications available.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
