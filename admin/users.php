<?php

require_once "../config/auth.php";
require_once "../config/db.php";

// Only admin can access this page
requireRole(["admin"]);


// Handle role/status update
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $userId = intval($_POST["userId"]);
    $role = $_POST["role"];
    $status = $_POST["status"];


    // Allowed roles
    $allowed_roles = [
        "admin",
        "chairman",
        "citizen"
    ];


    // Allowed statuses
    $allowed_status = [
        "active",
        "inactive"
    ];


    if (
        in_array($role, $allowed_roles) &&
        in_array($status, $allowed_status)
    ) {

        $stmt = $conn->prepare(
            "UPDATE users
             SET role = ?, status = ?
             WHERE userId = ?"
        );

        $stmt->bind_param(
            "ssi",
            $role,
            $status,
            $userId
        );

        $stmt->execute();

        $stmt->close();
    }


    header("Location: users.php");
    exit();
}


// Get all users
$sql = "
    SELECT
        userId,
        Firstname,
        Lastname,
        phoneNo,
        email,
        username,
        role,
        status,
        createdAt
    FROM users
    ORDER BY userId DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>User Management</title>


    <style>

        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap');

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #f4f6f8;
        }


        .header {

            background: #1f2937;

            color: white;

            padding: 18px 30px;

            display: flex;

            justify-content: space-between;

            align-items: center;
        }


        .header h2 {

            margin: 0;
        }


        .back {

            color: white;

            text-decoration: none;

            background: #a64b3c;

            padding: 9px 15px;

            border-radius: 5px;
        }


        .container {

            padding: 30px;

            max-width: 1400px;

            margin: auto;
        }


        .page-title {

            background: white;

            padding: 20px;

            border-radius: 10px;

            margin-bottom: 20px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }


        .table-container {

            background: white;

            border-radius: 10px;

            overflow-x: auto;

            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }


        table {

            width: 100%;

            border-collapse: collapse;

            min-width: 900px;
        }


        th,
        td {

            padding: 13px;

            border-bottom: 1px solid #eee;

            text-align: left;
        }


        th {

            background: #f8f9fa;

            font-weight: bold;
        }


        tr:hover {

            background: #f8f9fa;
        }


        select {

            padding: 7px;

            border: 1px solid #ccc;

            border-radius: 5px;
        }


        .update-btn {

            padding: 7px 12px;

            border: none;

            border-radius: 5px;

            background: #a64b3c;

            color: white;

            cursor: pointer;
        }


        .update-btn:hover {

            background: #0056b3;
        }


        .role {

            font-weight: bold;

            text-transform: capitalize;
        }


        .status {

            font-weight: bold;

            text-transform: capitalize;
        }


        @media (max-width: 768px) {

            .container {

                padding: 15px;
            }


            .header {

                padding: 15px;
            }

        }

        .add-user-btn {
    display: inline-block;
    padding: 10px 16px;
    background: #a64b3c;
    color: white;
    text-decoration: none;
    border-radius: 7px;
    font-weight: 600;
}

.add-user-btn:hover {
    background: #71352f;
}

        :root {
            --ink: #30242a;
            --muted: #75666a;
            --cream: #f7efe8;
            --paper: #fffdf9;
            --accent: #a64b3c;
            --accent-dark: #71352f;
            --coral: #c88b3a;
            --line: #ead9ce;
            --shadow: 0 18px 45px rgba(24, 51, 47, .08);
        }

        body {
            color: var(--ink);
            background: linear-gradient(135deg, #edf5f1 0%, #f7f5ee 55%, #eff6f7 100%);
            font-family: 'DM Sans', sans-serif;
        }

        .header {
            padding: 24px clamp(20px, 5vw, 64px);
            background: var(--accent-dark);
            box-shadow: 0 10px 30px rgba(23, 76, 61, .12);
        }

        .header h2 {
            font: 800 20px Manrope, sans-serif;
            letter-spacing: -.5px;
        }

        .back, .add-user-btn, .update-btn {
            border-radius: 9px;
            transition: .2s ease;
        }

        .back {
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .18);
        }

        .back:hover { background: rgba(255, 255, 255, .2); }

        .container {
            max-width: 1440px;
            padding: clamp(28px, 5vw, 58px);
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 28px 30px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--paper);
            box-shadow: var(--shadow);
        }

        .page-title::before {
            content: '01';
            display: grid;
            place-items: center;
            width: 54px;
            height: 54px;
            flex: 0 0 54px;
            color: white;
            background: var(--coral);
            border-radius: 14px;
            font: 800 16px Manrope, sans-serif;
        }

        .page-title h1 {
            margin: 0;
            font: 800 clamp(24px, 3vw, 36px) Manrope, sans-serif;
            letter-spacing: -1px;
        }

        .page-title p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .add-user-btn {
            order: 2;
            margin-left: auto;
            background: var(--accent);
        }

        .add-user-btn:hover { background: var(--accent-dark); }

        .table-container {
            margin-top: 20px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--paper);
            box-shadow: var(--shadow);
        }

        table { min-width: 1080px; }

        th {
            padding: 16px 14px;
            color: var(--muted);
            background: #f1f6f2;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        td {
            padding: 16px 14px;
            border-bottom-color: var(--line);
            font-size: 13px;
        }

        tbody tr { transition: .2s ease; }
        tbody tr:hover { background: #f7fbf8; }
        tbody tr:last-child td { border-bottom: 0; }

        td:first-child {
            color: var(--muted);
            font-weight: 700;
        }

        select {
            min-width: 108px;
            padding: 9px 11px;
            color: var(--ink);
            background: white;
            border: 1px solid #cbdad1;
            border-radius: 8px;
            font: 600 12px 'DM Sans', sans-serif;
        }

        select:focus {
            outline: 3px solid rgba(33, 107, 85, .14);
            border-color: var(--accent);
        }

        .update-btn {
            padding: 9px 14px;
            background: var(--coral);
            font: 700 12px 'DM Sans', sans-serif;
        }

        .update-btn:hover {
            background: #d96748;
            transform: translateY(-1px);
        }

        @media (max-width: 700px) {
            .header { align-items: flex-start; gap: 14px; }
            .header h2 { font-size: 17px; }
            .container { padding: 22px 14px; }
            .page-title { align-items: flex-start; flex-wrap: wrap; padding: 22px; }
            .page-title::before { width: 44px; height: 44px; flex-basis: 44px; }
            .page-title h1 { font-size: 25px; }
            .add-user-btn { order: 3; width: 100%; margin: 4px 0 0; text-align: center; }
        }

    </style>

</head>


<body>


<div class="header">

    <h2>Village Meeting <span style="color: #e6b879;">/ Admin</span></h2>

    <a
        href="dashboard.php"
        class="back"
    >
        ← Dashboard
    </a>

</div>


<div class="container">


    <div class="page-title">
        <div>
            <h1>User Management</h1>
            <p>Manage registered citizens and system staff.</p>
        </div>
        <a href="adduser.php" class="add-user-btn">+ Add User</a>

    </div>


    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Phone</th>

                    <th>Email</th>

                    <th>Username</th>

                    <th>Role</th>

                    <th>Status</th>

                    <th>Created</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

            <?php if ($result->num_rows > 0): ?>

                <?php while ($user = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $user["userId"]; ?>
                        </td>


                        <td>

                            <?php
                            echo htmlspecialchars(
                                $user["Firstname"] .
                                " " .
                                $user["Lastname"]
                            );
                            ?>

                        </td>


                        <td>

                            <?php
                            echo htmlspecialchars(
                                $user["phoneNo"]
                            );
                            ?>

                        </td>


                        <td>

                            <?php
                            echo htmlspecialchars(
                                $user["email"] ?? ""
                            );
                            ?>

                        </td>


                        <td>

                            <?php
                            echo htmlspecialchars(
                                $user["username"]
                            );
                            ?>

                        </td>


                        <td>

                            <form
                                method="POST"
                                action=""
                            >

                                <input
                                    type="hidden"
                                    name="userId"
                                    value="<?php
                                    echo $user["userId"];
                                    ?>"
                                >


                                <select name="role">

                                    <option
                                        value="citizen"
                                        <?php
                                        if (
                                            $user["role"]
                                            === "citizen"
                                        ) {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Citizen
                                    </option>


                                    <option
                                        value="chairman"
                                        <?php
                                        if (
                                            $user["role"]
                                            === "chairman"
                                        ) {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Chairman
                                    </option>


                                    <option
                                        value="admin"
                                        <?php
                                        if (
                                            $user["role"]
                                            === "admin"
                                        ) {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Admin
                                    </option>

                                </select>

                        </td>


                        <td>

                                <select name="status">

                                    <option
                                        value="active"
                                        <?php
                                        if (
                                            $user["status"]
                                            === "active"
                                        ) {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Active
                                    </option>


                                    <option
                                        value="inactive"
                                        <?php
                                        if (
                                            $user["status"]
                                            === "inactive"
                                        ) {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        Inactive
                                    </option>

                                </select>

                        </td>


                        <td>

                            <?php
                            echo htmlspecialchars(
                                $user["createdAt"]
                            );
                            ?>

                        </td>


                        <td>

                                <button
                                    type="submit"
                                    class="update-btn"
                                >
                                    Update
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td
                        colspan="9"
                        style="text-align:center;"
                    >
                        No users found.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


</body>

</html>