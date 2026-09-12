<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["chairman"]);

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["meeting_id"], $_POST["status"])) {
    $meeting_id = (int) $_POST["meeting_id"];
    $status = trim($_POST["status"]);
    $allowed_statuses = ["upcoming", "ongoing", "completed", "cancelled"];

    if ($meeting_id > 0 && in_array($status, $allowed_statuses, true)) {
        $stmt = $conn->prepare("UPDATE meetings SET Status = ? WHERE Meeting_id = ?");
        $stmt->bind_param("si", $status, $meeting_id);
        $updated = $stmt->execute();
        $message = $updated ? "Meeting status updated." : "Unable to update meeting status.";
        $message_type = $updated ? "success" : "error";
        $stmt->close();
    } else {
        $message = "Invalid meeting status.";
        $message_type = "error";
    }
}

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
    <title>Manage Meetings</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f7efe8; color: #30242a; }
        .wrap { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 16px; box-shadow: 0 12px 28px rgba(15,23,42,0.08); padding: 24px; }
        h1 { margin-top: 0; }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .topbar a, button { border: 0; background: #a64b3c; color: white; text-decoration: none; padding: 10px 18px; border-radius: 10px; cursor: pointer; font: inherit; }
        .topbar .secondary { background: #71352f; }
        .alert { margin-bottom: 18px; padding: 12px 14px; border-radius: 8px; font-size: 14px; }
        .alert.success { color: #7d3c2f; background: #fae5d7; }
        .alert.error { color: #991b1b; background: #fee2e2; }
        .row { padding: 18px 0; border-top: 1px solid #e5e7eb; }
        .row h3 { margin: 0 0 8px; }
        .meta { color: #54616d; font-size: 14px; margin-bottom: 8px; }
        .badge { display: inline-block; padding: 6px 10px; border-radius: 999px; background: #fff1d7; color: #8a5a2b; font-size: 12px; font-weight: bold; }
        .meeting-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 14px; }
        .meeting-actions form { display: flex; align-items: center; gap: 8px; }
        select { padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: white; }
        @media (max-width: 620px) { .topbar { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>
<div class="wrap">
    <div class="topbar">
        <h1>Meetings</h1>
        <div class="actions">
            <a href="../meetings/create.php">Create Meeting</a>
            <a class="secondary" href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
    <div class="card">
        <?php if ($message): ?>
            <div class="alert <?= htmlspecialchars($message_type) ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($meetings && $meetings->num_rows > 0): ?>
            <?php while ($meeting = $meetings->fetch_assoc()): ?>
                <div class="row">
                    <h3><?= htmlspecialchars($meeting['Title']) ?></h3>
                    <div class="badge"><?= htmlspecialchars($meeting['Status']) ?></div>
                    <div class="meta"><?= htmlspecialchars(date('d M Y', strtotime($meeting['Meetingdate']))) ?> • <?= htmlspecialchars(date('h:i A', strtotime($meeting['Meetingtime']))) ?> • <?= htmlspecialchars($meeting['Location']) ?></div>
                    <p><?= htmlspecialchars($meeting['Description'] ?? 'No description provided.') ?></p>
                    <div class="meeting-actions">
                        <form method="post">
                            <input type="hidden" name="meeting_id" value="<?= (int) $meeting['Meeting_id'] ?>">
                            <label for="status-<?= (int) $meeting['Meeting_id'] ?>">Status</label>
                            <select id="status-<?= (int) $meeting['Meeting_id'] ?>" name="status">
                                <?php foreach (['upcoming', 'ongoing', 'completed', 'cancelled'] as $status): ?>
                                    <option value="<?= $status ?>" <?= $meeting['Status'] === $status ? 'selected' : '' ?>><?= ucfirst($status) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit">Save</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No meetings available.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
