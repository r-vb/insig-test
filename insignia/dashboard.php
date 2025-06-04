<?php
// Include db.php which connects to the database using PDO
include 'db.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Establish a PDO connection
// try {
//     $conn = new PDO("pgsql:host=$host;dbname=$dbname", "$ude", "your_password");
//     // Set the PDO error mode to exception
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     echo "Connection failed: " . $e->getMessage();
// }

// Total registrations from all three tables
$stmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM solo_registrations) +
        (SELECT COUNT(*) FROM duo_registrations) +
        (SELECT COUNT(*) FROM group_registrations) AS total
");
$total_registrations = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Registrations per event (aggregated across all tables)
// Registrations per event (aggregated across all tables)
$stmt = $pdo->query("
    SELECT ename, SUM(total) AS total FROM (
        SELECT e.ename, COUNT(*) AS total
        FROM solo_registrations s JOIN insig_events e ON s.event_id = e.eid
        GROUP BY e.ename
        UNION ALL
        SELECT e.ename, COUNT(*) AS total
        FROM duo_registrations d JOIN insig_events e ON d.event_id = e.eid
        GROUP BY e.ename
        UNION ALL
        SELECT e.ename, COUNT(*) AS total
        FROM group_registrations g JOIN insig_events e ON g.event_id = e.eid
        GROUP BY e.ename
    ) AS combined
    GROUP BY ename
");

// Assign the result to the $registrations_per_event variable
$registrations_per_event = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Total amount collected from all tables
$stmt = $pdo->query("
    SELECT SUM(amount) AS total FROM (
        SELECT COUNT(*) * e.price AS amount
        FROM solo_registrations s
        JOIN insig_events e ON s.event_id = e.eid
        WHERE s.payment_status = 'Paid'
        GROUP BY e.eid, e.price

        UNION ALL

        SELECT COUNT(*) * e.price AS amount
        FROM duo_registrations d
        JOIN insig_events e ON d.event_id = e.eid
        WHERE d.payment_status = 'Paid'
        GROUP BY e.eid, e.price

        UNION ALL

        SELECT COUNT(*) * e.price AS amount
        FROM group_registrations g
        JOIN insig_events e ON g.event_id = e.eid
        WHERE g.payment_status = 'Paid'
        GROUP BY e.eid, e.price
    ) AS all_paid
");

$total_amount_collected = $stmt->fetch(PDO::FETCH_ASSOC)['total'];



// // Total amount collected from all tables
// $stmt = $conn->query("
//     SELECT 
//         COALESCE((SELECT SUM(payment_amount) FROM solo_registrations WHERE payment_status = 'Paid'), 0) +
//         COALESCE((SELECT SUM(payment_amount) FROM duo_registrations WHERE payment_status = 'Paid'), 0) +
//         COALESCE((SELECT SUM(payment_amount) FROM group_registrations WHERE payment_status = 'Paid'), 0) AS total
// ");
// $total_amount_collected = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Search participants across all three tables
$search_result = null;
if (isset($_POST['search'])) {
    $participant_name = "%" . $_POST['participant_name'] . "%";
    $stmt = $pdo->prepare("
        SELECT eid, s.eid, name AS name1, NULL AS name2, e.ename, payment_status, 'solo' AS type
        FROM solo_registrations s 
        JOIN insig_events e ON s.eid = e.eid
        WHERE name ILIKE :participant_name
    
        UNION ALL
    
        SELECT eid, d.eid, name1, name2, e.ename, payment_status, 'duo' AS type
        FROM duo_registrations d 
        JOIN insig_events e ON d.eid = e.eid
        WHERE name1 ILIKE :participant_name OR name2 ILIKE :participant_name
    
        UNION ALL
    
        SELECT id, g.eid, lead_name AS name1, NULL AS name2, e.ename, payment_status, 'group' AS type
        FROM group_registrations g 
        JOIN insig_events e ON g.eid = e.eid
        WHERE lead_name ILIKE :participant_name
    ");
    $stmt->bindParam(':participant_name', $participant_name, PDO::PARAM_STR);
    $stmt->execute();
    $search_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update payment status
if (isset($_POST['update_payment'])) {
    $registration_id = $_POST['registration_id'];
    $payment_status = $_POST['payment_status'];
    $table = $_POST['registration_type'];

    if (in_array($table, ['solo', 'duo', 'group'])) {
        $update_table = $table . '_registrations';
        $stmt = $pdo->prepare("UPDATE $update_table SET payment_status = :payment_status WHERE id = :registration_id");
        $stmt->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
        $stmt->bindParam(':registration_id', $registration_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
      * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background-color: #f4f4f4;
    padding: 30px;
}

h1 {
    text-align: center;
    margin-bottom: 40px;
    color: #333;
}

.dashboard {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.card-total, .card {
    background: white;
    width: 48%;
    padding: 20px;
    margin: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.card h3 {
    margin-bottom: 20px;
    font-size: 18px;
    color: #333;
}

table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ccc;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f0f0f0;
}

input, select, button {
    padding: 10px;
    margin: 5px 0;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049;
}

.update-form {
    margin-top: 20px;
}

.search-container {
    margin-bottom: 30px;
}

/* Button container for navigation */
.button-container {
    margin: 40px auto;
    text-align: center;
}

/* Optional styling if you add Total Amount content later */
.card-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-style: italic;
    height: 100px;
}

.card-highlight {
    background-color: #e8fff0;
    border-left: 5px solid #4CAF50;
}


    </style>
</head>
<body>

<h1>Admin Dashboard</h1>

<div class="dashboard">
    <!-- Total Registrations -->
    <div class="card-total">
        <h3>Total Number of Registrations</h3>
        <p><?php echo $total_registrations; ?></p>
    </div>

    <!-- Registrations Per Event -->
    <div class="card">
        <h3>Registrations Per Event</h3>
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Total Registrations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations_per_event as $row): ?>
                    <tr>
                        <td><?php echo $row['ename']; ?></td>
                        <td><?php echo $row['total']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Total Amount Collected -->
<div class="card card-highlight">
    <h3>Total Amount Collected (Paid Only)</h3>
    <p>₹<?php echo number_format($total_amount_collected); ?></p>
</div>


</div>
<div class="button-container">
    <a href="manage_participants.php">
        <button>Manage Participants</button>
    </a>
       <a href="adminLanding.php">
        <button style="margin-left: 10px;">Back to Landing Page</button>
    </a>
</div>


</body>
</html>
