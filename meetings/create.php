<?php

require_once "../config/db.php";
require_once "../config/auth.php";

requireRole(["chairman"]);

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $meeting_date = $_POST["meeting_date"];
    $meeting_time = $_POST["meeting_time"];
    $location = trim($_POST["location"]);

    // Validation
    if (
        empty($title) ||
        empty($meeting_date) ||
        empty($meeting_time) ||
        empty($location)
    ) {

        $message = "Please fill in all required fields.";
        $message_type = "error";

    } else {

        $created_by = $_SESSION["userId"];

        $sql = "INSERT INTO meetings
                (Title, Description, Meetingdate, Meetingtime, Location, CreatedBy)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {

            $stmt->bind_param(
                "sssssi",
                $title,
                $description,
                $meeting_date,
                $meeting_time,
                $location,
                $created_by
            );

            if ($stmt->execute()) {

                $message = "Meeting created successfully.";
                $message_type = "success";

                // Clear form values
                $title = "";
                $description = "";
                $meeting_date = "";
                $meeting_time = "";
                $location = "";

            } else {

                $message = "Failed to create meeting.";
                $message_type = "error";
            }

            $stmt->close();

        } else {

            $message = "Database error: " . $conn->error;
            $message_type = "error";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Create Meeting</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../assets/css/forms.css">

</head>

<body>

<div class="page-container">

    <div class="form-card">

        <div class="form-header">

            <h1>Create New Meeting</h1>

            <p>
                Create a new village meeting
            </p>

        </div>


        <?php if (!empty($message)): ?>

            <div class="alert <?= $message_type ?>">

                <?= htmlspecialchars($message) ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="form-group">

                <label for="title">
                    Meeting Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Enter meeting title"
                    value="<?= htmlspecialchars($title ?? '') ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Enter meeting description"
                ><?= htmlspecialchars($description ?? '') ?></textarea>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label for="meeting_date">
                        Meeting Date
                    </label>

                    <input
                        type="date"
                        id="meeting_date"
                        name="meeting_date"
                        value="<?= htmlspecialchars($meeting_date ?? '') ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="meeting_time">
                        Meeting Time
                    </label>

                    <input
                        type="time"
                        id="meeting_time"
                        name="meeting_time"
                        value="<?= htmlspecialchars($meeting_time ?? '') ?>"
                        required
                    >

                </div>

            </div>


            <div class="form-group">

                <label for="location">
                    Location
                </label>

                <input
                    type="text"
                    id="location"
                    name="location"
                    placeholder="Example: Village Hall"
                    value="<?= htmlspecialchars($location ?? '') ?>"
                    required
                >

            </div>


            <div class="form-actions">

                <a
                    href="../chairman/dashboard.php"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Meeting
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>