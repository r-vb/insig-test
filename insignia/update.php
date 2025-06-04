<?php
include 'db.php'; 
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}   

try {
    // Create a PDO connection
 

    // Fetch all events
    $events_sql = "SELECT * FROM insig_events";
    $events_result = $pdo->query($events_sql);

    // Fetch branches
    $branch_sql = "SELECT * FROM insig_branch";
    $branch_result = $pdo->query($branch_sql);

    // Fetch categories
    $category_sql = "SELECT * FROM insig_categories";
    $category_result = $pdo->query($category_sql);

    // Store branch names
    $branches = array();
    while ($branch = $branch_result->fetch(PDO::FETCH_ASSOC)) {
        $branches[$branch['bid']] = $branch['bname'];
    }

    // Store category names
    $categories = array();
    while ($category = $category_result->fetch(PDO::FETCH_ASSOC)) {
        $categories[$category['cid']] = $category['cname'];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Events</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: #f4f4f4;
            text-align: center;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
        }

        form {
            background: white;
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #218838;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        td a {
            color: #007bff;
            text-decoration: none;
        }

        td button {
            background: #ffc107;
            color: black;
        }

        td button:hover {
            background: #e0a800;
        }

        .logout {
            display: inline-block;
            margin-top: 20px;
            background: #dc3545;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
        }

        .logout:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <h2>Update Event</h2>

    <form action="update_pro.php" method="post">
        <input type="text" name="ENAME" id="ENAME" placeholder="Event Name (Required)" required>
        <textarea name="description" id="description" placeholder="Event Description (Leave blank to keep current)"></textarea>
        <input type="text" name="venue" id="venue" placeholder="Venue (Leave blank to keep current)">
        <input type="time" name="Timing" id="Timing">
        <input type="date" name="DATEE" id="DATEE">
        <input type="text" name="poster_link" id="poster_link" placeholder="Poster Image URL">
        <input type="url" name="Register" id="Register" placeholder="Registration Link">
        <input type="text" name="coordinator1" id="coordinator1" placeholder="Coordinator 1">
        <input type="text" name="coordinator2" id="coordinator2" placeholder="Coordinator 2">
        <input type="text" name="c1phone" id="c1phone" placeholder="Coordinator 1 Phone">
        <input type="text" name="c2phone" id="c2phone" placeholder="Coordinator 2 Phone">
        <textarea name="rules" id="rules" placeholder="Event Rules"></textarea>

        <select name="bid" id="bid">
            <option value="">Select Branch (Leave blank to keep current)</option>
            <?php foreach ($branches as $bid => $bname) { ?>
                <option value="<?php echo $bid; ?>"><?php echo $bname; ?></option>
            <?php } ?>
        </select>

        <select name="cid" id="cid">
            <option value="">Select Category (Leave blank to keep current)</option>
            <?php foreach ($categories as $cid => $cname) { ?>
                <option value="<?php echo $cid; ?>"><?php echo $cname; ?></option>
            <?php } ?>
        </select>

        <input type="text" name="price" id="price" placeholder="Price">
        <input type="number" name="participants" id="participants" placeholder="Max Participants">

        <button type="submit" name="update">Update Event</button>
    </form>

    <a class="logout" href="logout.php">Logout</a>
</body>
</html>
