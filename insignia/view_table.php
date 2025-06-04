<?php
include 'db.php'; // PDO connection
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

try {
    // Fetch all events
    $events_sql = "SELECT * FROM insig_events";
    $events_stmt = $pdo->query($events_sql);
    
    // Fetch branches
    $branch_sql = "SELECT * FROM insig_branch";
    $branch_stmt = $pdo->query($branch_sql);

    // Fetch categories
    $category_sql = "SELECT * FROM insig_categories";
    $category_stmt = $pdo->query($category_sql);

    // Store branch names
    $branches = array();
    while ($branch = $branch_stmt->fetch(PDO::FETCH_ASSOC)) {
        $branches[$branch['bid']] = $branch['bname'];
    }

    // Store category names
    $categories = array();
    while ($category = $category_stmt->fetch(PDO::FETCH_ASSOC)) {
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
    <title>View Table</title>
</head>
<style>
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

    h1 {
        text-align: center;
    }
</style>
<body>

<h1>Existing Events</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Venue</th>
        <th>Time</th>
        <th>Date</th>
        <th>Poster</th>
        <th>Register</th>
        <th>Coordinators</th>
        <th>Contacts</th>
        <th>Rules</th>
        <th>Branch</th>
        <th>Category</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>

    <?php 
    while ($event = $events_stmt->fetch(PDO::FETCH_ASSOC)) { 
    ?>
        <tr>
            <td><?php echo htmlspecialchars($event['eid']); ?></td>
            <td><?php echo htmlspecialchars($event['ename']); ?></td>
            <td><?php echo htmlspecialchars($event['description']); ?></td>
            <td><?php echo htmlspecialchars($event['venue']); ?></td>
            <td><?php echo htmlspecialchars($event['timing']); ?></td>
            <td><?php echo htmlspecialchars($event['datee']); ?></td>
            <td>
                <a href="<?php echo htmlspecialchars($event['poster_link']); ?>" target="_blank">View</a>
            </td>
            <td>
                <a href="<?php echo htmlspecialchars($event['register']); ?>" target="_blank">Register</a>
            </td>
            <td><?php echo htmlspecialchars($event['coordinator1'] . ", " . $event['coordinator2']); ?></td>
            <td><?php echo htmlspecialchars($event['c1phone'] . ", " . $event['c2phone']); ?></td>
            <td><?php echo htmlspecialchars($event['rules']); ?></td>
            <td><?php echo htmlspecialchars($branches[$event['bid']] ?? "N/A"); ?></td>
            <td><?php echo htmlspecialchars($categories[$event['cid']] ?? "N/A"); ?></td>
            <td><?php echo htmlspecialchars($event['price']); ?></td>
            <td>
                <a href="add_event.php?delete=<?php echo urlencode($event['eid']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php } ?>

</table>
</body>
</html>
