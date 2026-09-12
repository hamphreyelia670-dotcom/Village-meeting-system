<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["citizen"]);

$announcements = $conn->query(
    "SELECT AnnouncementId, Title, Message, Createdat
     FROM announcement
     WHERE Published = 1
     ORDER BY Createdat DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
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
        <h1>Village Announcements</h1>
        <?php if ($announcements && $announcements->num_rows > 0): ?>
            <?php while ($item = $announcements->fetch_assoc()): ?>
                <div class="item">
                    <h3><?= htmlspecialchars($item['Title']) ?></h3>
                    <div class="date"><?= htmlspecialchars(date('d M Y', strtotime($item['Createdat']))) ?></div>
                    <p><?= htmlspecialchars($item['Message']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No announcements published yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
