<?php

session_start();

require_once "../config/db.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];


    // Check empty fields
    if (empty($username) || empty($password)) {

        $message = "Please enter username and password.";
        $message_type = "error";

    } else {

        // Find user
        $stmt = $conn->prepare(
            "SELECT userId, Firstname, Lastname, username, password, role, status
             FROM users
             WHERE username = ?"
        );

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();


            // Check account status
            if ($user["status"] !== "active") {

                $message = "Your account is inactive.";
                $message_type = "error";

            }

            // Verify password
            elseif (password_verify($password, $user["password"])) {

                // Create session
                $_SESSION["userId"] = $user["userId"];
                $_SESSION["Firstname"] = $user["Firstname"];
                $_SESSION["Lastname"] = $user["Lastname"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];


                // Redirect according to role

                if ($user["role"] === "admin") {

                    header("Location: ../admin/dashboard.php");
                    exit();

                }

                elseif ($user["role"] === "chairman") {

                    header("Location: ../chairman/dashboard.php");
                    exit();

                }

                elseif ($user["role"] === "citizen") {

                    header("Location: ../citizen/dashboard.php");
                    exit();

                }

                else {

                    session_unset();
                    session_destroy();
                    $message = "This account has no valid system role.";
                    $message_type = "error";

                }

            }

            else {

                $message = "Invalid username or password.";
                $message_type = "error";
            }

        }

        else {

            $message = "Invalid username or password.";
            $message_type = "error";
        }


        $stmt->close();
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - Village Meeting System</title>


    <style>

        * {
            box-sizing: border-box;
        }
       body header {
            text-align: center;
            margin-bottom: 20px;
            position: top;
        }
        footer{
            background-color: #bb949a;
            color: white;
            text-align: center;
        
        }


        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #f7efe8;
            background-image: url("WhatsApp\ Image\ 2026-09-09\ at\ 11.48.07\ AM.jpeg");
            background-size: left;
            background-position: left;

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;
        }


        .login-container {
            transition: 0.3s;

            width: 100%;

            max-width: 400px;

            background: white;

            padding: 30px;

            border-radius: 10px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }


        h2 {

            text-align: center;
            color: #71352f;

            margin-bottom: 10px;
        }


        .subtitle {

            text-align: center;

            color: #dc1414;

            margin-bottom: 25px;
        }


        .form-group {

            margin-bottom: 18px;
        }


        label {

            display: block;

            margin-bottom: 6px;

            font-weight: bold;
        }


        input {

            width: 100%;

            padding: 11px;

            border: 1px solid #ccc;

            border-radius: 5px;

            font-size: 15px;
        }


        input:focus {

            outline: none;

            border-color: #a64b3c;
        }


        button {

            width: 100%;

            padding: 12px;

            border: none;

            border-radius: 5px;

            background: #a64b3c;

            color: white;

            font-size: 16px;

            cursor: pointer;
        }


        button:hover {

            background: #71352f;
        }


        .message {

            padding: 10px;

            margin-bottom: 15px;

            border-radius: 5px;

            text-align: center;
        }


        .error {

            background: #f8d7da;

            color: #f5061e;
        }


        .register-link {

            text-align: center;

            margin-top: 20px;
        }


        .register-link a {

            color: #a64b3c;

            text-decoration: none;
        }

    </style>

</head>


<body>
    <header>
        <h1>JAMHURI YA MUUNGANO WA TANZANIA</h1>
        <h2>TAWALA ZA MIKOA NA SERIKALI ZA MITAA</h2>
        <h3>WILAYA YA DODOMA MJINI</h3>
        <h4>Ofisi ya kijiji cha ngh'ongh'onha</h4>


    </header>


<div class="login-container">

    <h2> Welcome to Village Meeting System</h2>

    <div class="subtitle">
         Enter your credentials to login
    </div>


    <?php if (!empty($message)): ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <form method="POST" action="">


        <div class="form-group">

            <label for="username">
                Username
            </label>

            <input
                type="text"
                id="username"
                name="username"
                required
            >

        </div>


        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >

        </div>


        <button type="submit">
            Login
        </button>


    </form>


    <div class="register-link">

        Don't have an account?

        <a href="register.php">
            Register
        </a>

    </div>
    <div>
        <footer>
            <p>&copy; 2026 Village Meeting System. All rights reserved.</p>
        </footer>
    </div>


</div>


</body>

</html>