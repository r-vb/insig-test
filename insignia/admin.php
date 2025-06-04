<?php
session_start();
require 'db.php'; // Include your database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

try {
    // Fetch all events
    $events_sql = "SELECT * FROM insig_events";
// ✅ Correct
$events_stmt = $pdo->prepare($events_sql);
    $events_stmt->execute();
    $events_result = $events_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch branches
    $branch_sql = "SELECT * FROM insig_branch";
    $branch_stmt = $pdo->prepare($branch_sql);
    $branch_stmt->execute();
    $branch_result = $branch_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch categories
    $category_sql = "SELECT * FROM insig_categories";
    $category_stmt = $pdo->prepare($category_sql);
    $category_stmt->execute();
    $category_result = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Store branch names
    $branches = array();
    foreach ($branch_result as $branch) {
        $branches[$branch['bid']] = $branch['bname'];
    }

    // Store category names
    $categories = array();
    foreach ($category_result as $category) {
        $categories[$category['cid']] = $category['cname'];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
        }

        header h2 {
            margin: 0;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        form input,
        form select,
        form textarea,
        form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            background-color: #2ecc71;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #27ae60;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .actions a {
            color: #e74c3c;
            text-decoration: none;
            margin-right: 10px;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .edit-btn {
            background-color: #3498db;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .edit-btn:hover {
            background-color: #2980b9;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            padding: 10px;
            background-color: #34495e;
            color: white;
        }
    </style>
</head>

<body>

    <header>
        <h2>Manage Events - Admin Panel</h2>
    </header>

    <div class="container">

        <h3>Add/Update Event</h3>
        <form action="admin_process.php" method="post">
            <input type="hidden" name="EID" id="EID">
            <input type="text" name="ENAME" id="ENAME" placeholder="Event Name" required>
            <textarea name="description" id="description" placeholder="Event Description"></textarea>
            <input type="text" name="venue" id="venue" placeholder="Venue">
            <input type="text" name="Timing" id="Timing" placeholder="Timing">
            <input type="date" name="DATEE" id="DATEE">
            <input type="text" name="poster_link" id="poster_link" placeholder="Poster Image URL">
            <input type="url" name="Register" id="Register" placeholder="Registration Link">
            <input type="text" name="coordinator1" id="coordinator1" placeholder="Coordinator 1">
            <input type="text" name="coordinator2" id="coordinator2" placeholder="Coordinator 2">
            <input type="text" name="c1phone" id="c1phone" placeholder="Coordinator 1 Phone">
            <input type="text" name="c2phone" id="c2phone" placeholder="Coordinator 2 Phone">
            <textarea name="rules" id="rules" placeholder="Event Rules"></textarea>

            <select name="bid" id="bid">
                <option value="">Select Branch</option>
                <?php foreach ($branches as $bid => $bname) { ?>
                    <option value="<?php echo $bid; ?>"><?php echo $bname; ?></option>
                <?php } ?>
            </select>

            <select name="cid" id="cid">
                <option value="">Select Category</option>
                <?php foreach ($categories as $cid => $cname) { ?>
                    <option value="<?php echo $cid; ?>"><?php echo $cname; ?></option>
                <?php } ?>
            </select>

            <input type="text" name="price" id="price" placeholder="Price">
            <input type="number" name="participants" id="participants" placeholder="Max Participants">
            <button type="submit" name="submit">Add/Update Event</button>
        </form>

    </div>

    <footer>
        <p>&copy; 2025 Admin Panel | College Fest Management</p>
    </footer>

    <script>
        function editEvent(EID, ENAME, description, venue, Timing, DATEE, poster_link, Register, coordinator1, coordinator2, c1phone, c2phone, rules, bid, cid, price, participants) {
            document.getElementById('EID').value = EID;
            document.getElementById('ENAME').value = ENAME;
            document.getElementById('description').value = description;
            document.getElementById('venue').value = venue;
            document.getElementById('Timing').value = Timing;
            document.getElementById('DATEE').value = DATEE;
            document.getElementById('poster_link').value = poster_link;
            document.getElementById('Register').value = Register;
            document.getElementById('coordinator1').value = coordinator1;
            document.getElementById('coordinator2').value = coordinator2;
            document.getElementById('c1phone').value = c1phone;
            document.getElementById('c2phone').value = c2phone;
            document.getElementById('rules').value = rules;
            document.getElementById('bid').value = bid;
            document.getElementById('cid').value = cid;
            document.getElementById('price').value = price;
            document.getElementById('participants').value = participants;
        }
    </script>

</body>

</html>

<?php $conn = null; // Close the PDO connection ?>
