<?php

require_once "../config/auth.php";
require_once "../config/db.php";

requireRole(["admin"]);

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $Firstname = trim($_POST["Firstname"]);
    $Lastname = trim($_POST["Lastname"]);
    $phoneNo = trim($_POST["phoneNo"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = $_POST["role"];
    $status = $_POST["status"];


    /*
    |--------------------------------------------------
    | Validate required fields
    |--------------------------------------------------
    */

    if (
        empty($Firstname) ||
        empty($Lastname) ||
        empty($phoneNo) ||
        empty($username) ||
        empty($password) ||
        empty($confirm_password) ||
        empty($role) ||
        empty($status)
    ) {

        $message = "Please fill in all required fields.";
        $message_type = "error";

    }

    /*
    |--------------------------------------------------
    | Validate role
    |--------------------------------------------------
    */

    elseif (!in_array($role, ["admin", "chairman", "citizen"])) {

        $message = "Invalid role selected.";
        $message_type = "error";

    }

    /*
    |--------------------------------------------------
    | Validate status
    |--------------------------------------------------
    */

    elseif (!in_array($status, ["active", "inactive"])) {

        $message = "Invalid account status.";
        $message_type = "error";

    }

    /*
    |--------------------------------------------------
    | Check password
    |--------------------------------------------------
    */

    elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    }

    elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $message_type = "error";

    }

    else {

        /*
        |--------------------------------------------------
        | Check duplicate username, phone or email
        |--------------------------------------------------
        */

        $check = $conn->prepare(
            "SELECT userId
             FROM users
             WHERE username = ?
                OR phoneNo = ?
                OR (email IS NOT NULL AND email = ?)"
        );

        $check->bind_param(
            "sss",
            $username,
            $phoneNo,
            $email
        );

        $check->execute();

        $result = $check->get_result();


        if ($result->num_rows > 0) {

            $message = "Username, phone number or email already exists.";
            $message_type = "error";

        }

        else {

            /*
            |--------------------------------------------------
            | Hash password
            |--------------------------------------------------
            */

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            /*
            |--------------------------------------------------
            | Insert new staff account
            |--------------------------------------------------
            */

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

                $message = "User account created successfully.";
                $message_type = "success";

                // Clear form values
                $Firstname = "";
                $Lastname = "";
                $phoneNo = "";
                $email = "";
                $username = "";

            } else {

                $message = "Failed to create user account.";
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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add User - Village Meeting System</title>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap');

        :root {
            --ink: #30242a;
            --muted: #75666a;
            --sage: #f7efe8;
            --paper: #ffffff;
            --card: rgba(255, 255, 255, 0.9);
            --accent: #a64b3c;
            --accent-soft: #fae5d7;
            --accent-deep: #71352f;
            --line: #ead9ce;
            --coral: #c88b3a;
            --danger: #d74d4d;
            --danger-soft: #fdf0f0;
            --success: #8a5a2b;
            --success-soft: #fff1d7;
            --shadow: 0 20px 40px rgba(79, 45, 38, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background: linear-gradient(135deg, #f7efe8 0%, #fbf4ec 50%, #f2e4dd 100%);
        }

        .container {
            width: 100%;
            max-width: 960px;
            margin: 48px auto;
            padding: 20px;
        }

        .card {
            background: var(--card);
            border: 1px solid rgba(160, 184, 174, 0.35);
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            padding: 28px 30px 22px;
            background: linear-gradient(135deg, rgba(166, 75, 60, 0.97), rgba(113, 53, 47, 0.97));
            color: white;
        }

        .header-copy {
            flex: 1;
        }

        .header-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.12);
            color: #fff1df;
        }

        .header h2 {
            margin: 0 0 6px;
            font-family: 'Manrope', sans-serif;
            font-size: clamp(28px, 3vw, 36px);
            letter-spacing: -1px;
        }

        .header p {
            margin: 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 14px;
        }

        .header-badge {
            display: grid;
            place-items: center;
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.16);
            font-size: 24px;
            font-weight: 700;
        }

        .form-wrap {
            padding: 28px 30px 30px;
        }

        .message {
            margin-bottom: 22px;
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }

        .success {
            background: var(--success-soft);
            color: var(--success);
            border: 1px solid rgba(29, 122, 79, 0.18);
        }

        .error {
            background: var(--danger-soft);
            color: var(--danger);
            border: 1px solid rgba(215, 77, 77, 0.15);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 13px;
            font-weight: 700;
            color: #2f473f;
        }

        input,
        select {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #f8faf9;
            color: var(--ink);
            font-size: 15px;
            transition: all 0.2s ease;
        }

        input::placeholder {
            color: #8ea09a;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: rgba(29, 91, 79, 0.8);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(29, 91, 79, 0.08);
        }

        .buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 28px;
        }

        button,
        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 12px 18px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button {
            background: linear-gradient(135deg, var(--accent), #c36b4d);
            color: white;
            box-shadow: 0 12px 25px rgba(29, 91, 79, 0.2);
        }

        .back-btn {
            background: var(--accent-soft);
            color: var(--accent-deep);
            border: 1px solid rgba(29, 91, 79, 0.12);
        }

        button:hover,
        .back-btn:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 700px) {
            .container {
                margin: 24px auto;
                padding: 12px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-wrap {
                padding: 22px 18px 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .buttons {
                flex-direction: column-reverse;
            }

            button,
            .back-btn {
                width: 100%;
            }
        }
    </style>

</head>


<body>


<div class="container">

    <div class="card">

        <div class="header">

            <div class="header-copy">
                <span class="header-tag">Admin Access</span>
                <h2>Add System User</h2>
                <p>
                    Create an account for an approved system role.
                </p>
            </div>

            <div class="header-badge">＋</div>

        </div>


        <?php if (!empty($message)): ?>

            <div class="message <?php echo $message_type; ?>">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <form method="POST" action="">


            <div class="form-grid">


                <!-- First Name -->

                <div class="form-group">

                    <label for="Firstname">
                        First Name
                    </label>

                    <input
                        type="text"
                        id="Firstname"
                        name="Firstname"
                        value="<?php echo htmlspecialchars($Firstname ?? ''); ?>"
                        required
                    >

                </div>


                <!-- Last Name -->

                <div class="form-group">

                    <label for="Lastname">
                        Last Name
                    </label>

                    <input
                        type="text"
                        id="Lastname"
                        name="Lastname"
                        value="<?php echo htmlspecialchars($Lastname ?? ''); ?>"
                        required
                    >

                </div>


                <!-- Phone -->

                <div class="form-group">

                    <label for="phoneNo">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        id="phoneNo"
                        name="phoneNo"
                        placeholder="07XXXXXXXX"
                        value="<?php echo htmlspecialchars($phoneNo ?? ''); ?>"
                        required
                    >

                </div>


                <!-- Email -->

                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="example@gmail.com"
                        value="<?php echo htmlspecialchars($email ?? ''); ?>"
                    >

                </div>


                <!-- Username -->

                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?php echo htmlspecialchars($username ?? ''); ?>"
                        required
                    >

                </div>


                <!-- Role -->

                <div class="form-group">

                    <label for="role">
                        Role
                    </label>

                    <select
                        id="role"
                        name="role"
                        required
                    >

                        <option value="" <?php echo (!isset($role) || $role === '') ? 'selected' : ''; ?>>
                            Select Role
                        </option>

                        <option value="chairman" <?php echo (isset($role) && $role === 'chairman') ? 'selected' : ''; ?>>
                            Chairman
                        </option>

                        <option value="citizen" <?php echo (isset($role) && $role === 'citizen') ? 'selected' : ''; ?>>
                            Citizen
                        </option>

                        <option value="admin" <?php echo (isset($role) && $role === 'admin') ? 'selected' : ''; ?>>
                            Administrator
                        </option>

                    </select>

                </div>


                <!-- Password -->

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


                <!-- Confirm Password -->

                <div class="form-group">

                    <label for="confirm_password">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        required
                    >

                </div>


                <!-- Status -->

                <div class="form-group full">

                    <label for="status">
                        Account Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                    >

                        <option value="active" <?php echo (isset($status) && $status === 'active') || !isset($status) ? 'selected' : ''; ?>>
                            Active
                        </option>

                        <option value="inactive" <?php echo (isset($status) && $status === 'inactive') ? 'selected' : ''; ?>>
                            Inactive
                        </option>

                    </select>

                </div>


            </div>


            <div class="buttons">

                <a
                    href="users.php"
                    class="back-btn"
                >
                    ← Back to Users
                </a>


                <button type="submit">
                    Create Account
                </button>

            </div>


        </form>

    </div>

</div>


</body>

</html>