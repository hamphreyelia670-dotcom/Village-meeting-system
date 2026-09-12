<?php

require_once "../config/auth.php";

// Only citizens can access this page
requireRole(["citizen"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Citizen Dashboard</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .navbar {
            background: #71352f;
            color: white;
            padding: 15px 30px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .logout {
            color: white;
            text-decoration: none;
            background: #dc3545;
            padding: 8px 15px;
            border-radius: 5px;
        }

        .container {
            padding: 30px;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            
        }

        .card {
            background: white;
            padding: 25px;
            
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin-top: 0;
        }

        .card a {
            text-decoration: none;
            color: #a64b3c;
        }

        body { background: #f7efe8; color: #30242a; }
        .navbar { background: #71352f; }
        .logout { background: #a64b3c; }
        .card a { color: #a64b3c; }
    </style>

</head>

<body>


<nav class="navbar">

    <h2>Village Meeting System</h2>

    <a href="../auth/logout.php" class="logout">
        Logout
    </a>

</nav>


<div class="container">


    <div class="welcome">

        <h2>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["Firstname"]); ?>
        </h2>

        <p>
            You are logged in as a
            <strong>
                <?php echo htmlspecialchars($_SESSION["role"]); ?>
            </strong>.
        </p>

    </div>


    <div class="cards">


        <div class="card">

            <h3>Meetings</h3>

            <p>
                View upcoming and previous village meetings.
            </p>

            <a href="meetings.php">
                View Meetings
            </a>

        </div>


        <div class="card">

            <h3>Announcements</h3>

            <p>
                View important village announcements.
            </p>

            <a href="announcements.php">
                View Announcements
            </a>

        </div>


        <div class="card">

            <h3>Minutes</h3>

            <p>
                View published meeting minutes.
            </p>

            <a href="minutes.php">
                View Minutes
            </a>

        </div>


        <div class="card">

            <h3>Resolutions</h3>

            <p>
                View decisions and resolutions from meetings.
            </p>

            <a href="resolutions.php">
                View Resolutions
            </a>

        </div>


        <div class="card">

            <h3>Notifications</h3>

            <p>
                View your village notifications.
            </p>

            <a href="notifications.php">
                View Notifications
            </a>

        </div>


        <div class="card">

            <h3>Feedback</h3>

            <p>
                Send questions, suggestions or complaints.
            </p>

            <a href="feedback.php">
                Send Feedback
            </a>

        </div>


    </div>

</div>


</body>

</html>