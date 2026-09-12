<?php

require_once "../config/db.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get data from form
    $Firstname = trim($_POST["Firstname"]);
    $Lastname = trim($_POST["Lastname"]);
    $phoneNo = trim($_POST["phoneNo"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];


    // Check required fields
    if (
        empty($Firstname) ||
        empty($Lastname) ||
        empty($phoneNo) ||
        empty($username) ||
        empty($password) ||
        empty($confirm_password)
    ) {

        $message = "Please fill in all required fields.";
        $message_type = "error";

    }

    // Check password
    elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    }

    // Check password length
    elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $message_type = "error";

    }

    else {

        // Check username or phone number
        $check = $conn->prepare(
            "SELECT userId
             FROM users
             WHERE username = ? OR phoneNo = ?"
        );

        $check->bind_param(
            "ss",
            $username,
            $phoneNo
        );

        $check->execute();

        $result = $check->get_result();


        if ($result->num_rows > 0) {

            $message = "Username or phone number already exists.";
            $message_type = "error";

        }

        else {

            // Hash password
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            // New users are citizens
            $role = "citizen";

            $status = "active";


            // Insert user
            $stmt = $conn->prepare(
                "INSERT INTO users
                (
                    Firstname,
                    Lastname,
                    phoneNo,
                    email,
                    username,
                    password,
                    role,
                    status
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );


            $stmt->bind_param(
                "ssssssss",
                $Firstname,
                $Lastname,
                $phoneNo,
                $email,
                $username,
                $hashed_password,
                $role,
                $status
            );


            if ($stmt->execute()) {

                $message = "Registration successful! You can now login.";
                $message_type = "success";

            }

            else {

                $message = "Registration failed. Please try again.";
                $message_type = "error";
            }


            $stmt->close();
        }


        $check->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Citizen Registration</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;

            font-family: Arial, sans-serif;

            background: #f7efe8;

            display: flex;

            justify-content: center;

            align-items: center;

            min-height: 100vh;
        }


        .register-container {

            width: 100%;

            max-width: 500px;

            background: #fffaf5;

            padding: 30px;

            border-radius: 10px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.1);
        }


        h2 {

            text-align: center;
            color: #71352f;

            margin-bottom: 25px;
        }


        .form-group {

            margin-bottom: 15px;
            margin-top: 30px;
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


        .success {

            background: #d4edda;

            color: #155724;
        }


        .error {

            background: #f8d7da;

            color: #721c24;
        }


        .login-link {

            text-align: center;

            margin-top: 20px;
        }


        .login-link a {

            color: #a64b3c;

            text-decoration: none;
        }

    </style>

</head>


<body>


<div class="register-container">

    <h2>Citizen Registration</h2>


    <?php if (!empty($message)): ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <form method="POST" action="">


        <div class="form-group">

            <label>First Name</label>

            <input
                type="text"
                name="Firstname"
                required
            >

        </div>


        <div class="form-group">

            <label>Last Name</label>

            <input
                type="text"
                name="Lastname"
                required
            >

        </div>


        <div class="form-group">

            <label>Phone Number</label>

            <input
                type="text"
                name="phoneNo"
                placeholder="07XXXXXXXX"
                required
            >

        </div>


        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="example@gmail.com"
            >

        </div>


        <div class="form-group">

            <label>Username</label>

            <input
                type="text"
                name="username"
                required
            >

        </div>


        <div class="form-group">

            <label>Password</label>

            <input
                type="password"
                name="password"
                required
            >

        </div>


        <div class="form-group">

            <label>Confirm Password</label>

            <input
                type="password"
                name="confirm_password"
                required
            >

        </div>


        <button type="submit">
            Register
        </button>

    </form>


    <div class="login-link">

        Already have an account?

        <a href="login.php">
            Login
        </a>

    </div>

</div>


</body>

</html>