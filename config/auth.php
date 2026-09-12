<?php

session_start();


// Check if user is logged in
function requireLogin()
{
    if (!isset($_SESSION["userId"])) {

        header("Location: ../auth/login.php");
        exit();

    }
}


// Check if user has the required role
function requireRole($allowed_roles)
{
    requireLogin();

    if (!in_array($_SESSION["role"], $allowed_roles)) {

        http_response_code(403);

        die("Access Denied. You do not have permission to access this page.");

    }
}

?>