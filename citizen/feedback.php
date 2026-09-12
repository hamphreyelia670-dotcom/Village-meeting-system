<?php
require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["citizen"]);

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($subject) || empty($message)) {
        $message = 'Please fill in both subject and message.';
        $message_type = 'error';
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO feedback (UserId, Subject, Message, Status) VALUES (?, ?, ?, 'pending')"
        );
        $stmt->bind_param('iss', $_SESSION['userId'], $subject, $message);

        if ($stmt->execute()) {
            $message = 'Your feedback has been submitted successfully.';
            $message_type = 'success';
            $_POST = [];
        } else {
            $message = 'Unable to send feedback. Please try again.';
            $message_type = 'error';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Feedback</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; }
        .wrap { max-width: 700px; margin: 40px auto; padding: 0 20px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 8px 18px rgba(0,0,0,0.06); padding: 24px; }
        h1 { margin-bottom: 20px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; }
        textarea { min-height: 150px; resize: vertical; }
        button { background: #a64b3c; color: white; border: none; padding: 12px 18px; border-radius: 8px; cursor: pointer; }
        .message { padding: 12px 14px; border-radius: 8px; margin-bottom: 18px; }
        .success { background: #e7f7ee; color: #166534; }
        .error { background: #fdecec; color: #991b1b; }
        a.back { display: inline-block; margin-bottom: 20px; color: #0a58ca; text-decoration: none; }
        body { background: #f7efe8; color: #30242a; }
        button { background: #a64b3c; }
        a.back { color: #a64b3c; }
        .success { background: #fae5d7; color: #7d3c2f; }
    </style>
</head>
<body>
<div class="wrap">
    <a class="back" href="dashboard.php">← Back to dashboard</a>
    <div class="card">
        <h1>Send Feedback</h1>

        <?php if (!empty($message)): ?>
            <div class="message <?= htmlspecialchars($message_type) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Enter subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>
            </div>
            <button type="submit">Send Feedback</button>
        </form>
    </div>
</div>
</body>
</html>
