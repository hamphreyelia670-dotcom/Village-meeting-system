<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["citizen"]);

$meetings = $conn->query(
    "SELECT Meeting_id, Title, Description, Meetingdate, Meetingtime, Location, Status
     FROM meetings
     ORDER BY Meetingdate DESC, Meetingtime DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Meetings</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; }
        .wrap { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 8px 18px rgba(0,0,0,0.06); padding: 24px; }
        h1 { margin-bottom: 20px; }
        .meta { display: flex; gap: 12px; flex-wrap: wrap; margin: 10px 0; color: #555; }
        .row { border-top: 1px solid #eee; padding: 16px 0; }
        .badge { display: inline-block; background: #e9f5ef; color: #166534; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: bold; }
        a.back { display: inline-block; margin-bottom: 20px; color: #0a58ca; text-decoration: none; }
        body { background: #f7efe8; color: #30242a; }
        a.back { color: #a64b3c; }
        .row { border-top-color: #ead9ce; }
        .meta { color: #75666a; }
        .badge { background: #fff1d7; color: #8a5a2b; }
    </style>
</head>
<body>
<div class="wrap">
    <a class="back" href="dashboard.php">← Back to dashboard</a>
    <div class="card">
        <h1>Village Meetings</h1>
        <?php if ($meetings && $meetings->num_rows > 0): ?>
            <?php while ($meeting = $meetings->fetch_assoc()): ?>
                <div class="row">
                    <h3><?= htmlspecialchars($meeting['Title']) ?></h3>
                    <div class="badge"><?= htmlspecialchars($meeting['Status'] ?? 'upcoming') ?></div>
                    <div class="meta">
                        <span><?= htmlspecialchars(date('d M Y', strtotime($meeting['Meetingdate']))) ?></span>
                        <span><?= htmlspecialchars(date('h:i A', strtotime($meeting['Meetingtime']))) ?></span>
                        <span><?= htmlspecialchars($meeting['Location']) ?></span>
                    </div>
                    <p><?= htmlspecialchars($meeting['Description'] ?? 'No description provided.') ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No meetings available yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
