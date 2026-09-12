<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["chairman"]);

$stmt = $conn->prepare(
    "SELECT userId, Firstname, Lastname, phoneNo, email, username, role, status FROM users WHERE userId = ?"
);
$stmt->bind_param('i', $_SESSION['userId']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chairman Profile</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f3f7fa; }
        .wrap { max-width: 900px; margin: 50px auto; padding: 0 20px; }
        .card { background: white; border-radius: 18px; box-shadow: 0 12px 28px rgba(15,23,42,0.08); padding: 30px; }
        h1 { margin-top: 0; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
        .item { background: #f8fafc; padding: 16px; border-radius: 12px; }
        .label { color: #5b6b74; font-size: 12px; text-transform: uppercase; font-weight: bold; }
        .value { margin-top: 8px; font-size: 18px; font-weight: 600; }
        a { display: inline-block; margin-top: 24px; background: #a64b3c; color: white; text-decoration: none; padding: 12px 18px; border-radius: 10px; }
        @media (max-width: 600px) { .grid { grid-template-columns: 1fr; } }
        body { background: #f7efe8; color: #30242a; }
        .item { background: #fff1e5; }
        .label { color: #75666a; }
        a { background: #a64b3c; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Profile</h1>
        <?php if ($user): ?>
            <div class="grid">
                <div class="item">
                    <div class="label">First Name</div>
                    <div class="value"><?= htmlspecialchars($user['Firstname']) ?></div>
                </div>
                <div class="item">
                    <div class="label">Last Name</div>
                    <div class="value"><?= htmlspecialchars($user['Lastname']) ?></div>
                </div>
                <div class="item">
                    <div class="label">Username</div>
                    <div class="value"><?= htmlspecialchars($user['username']) ?></div>
                </div>
                <div class="item">
                    <div class="label">Role</div>
                    <div class="value"><?= htmlspecialchars($user['role']) ?></div>
                </div>
                <div class="item">
                    <div class="label">Phone</div>
                    <div class="value"><?= htmlspecialchars($user['phoneNo']) ?></div>
                </div>
                <div class="item">
                    <div class="label">Email</div>
                    <div class="value"><?= htmlspecialchars($user['email'] ?? 'Not provided') ?></div>
                </div>
                <div class="item">
                    <div class="label">Status</div>
                    <div class="value"><?= htmlspecialchars($user['status']) ?></div>
                </div>
            </div>
        <?php else: ?>
            <p>No profile data found.</p>
        <?php endif; ?>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
