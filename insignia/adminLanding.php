<?php
include 'db.php'; // Database connection
session_start();

// Check if admin is logged in
// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php"); // Redirect to login if not authenticated
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
        }

        .card {
            background: white;
            width: 300px;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            margin: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        .card:hover {
            transform: scale(1.05);
            background: #ff9966;
            color: white;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

    </style>
</head>
<body>

    <h1>Admin Page</h1>

    <!-- Add Event Card -->
    <a href="admin.php">
        <div class="card" onclick="jumpToAdd()">
            Add an Event
        </div>
    </a>

    <!-- Update Event Card -->
    <a href="update.php">
        <div class="card">
            Update Existing Event
        </div>
    </a>
    <a href="view_table.php">
        <div class="card">
           View Table
        </div>
    </a>
    <a href="dashboard.php">
        <div class="card">
           Dashboard
        </div>
    </a>

    <button>
    <a href="logout.php">
    <button>Logout</button>
</a>

    </button>

</body>
</html>
