<?php
// Include the database connection
include 'db.php'; 
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Initialize search result
$search_result = null;
$participant_name = "";

// Handle the search request
if (isset($_POST['search'])) {
    $participant_name = $_POST['participant_name'];
    $search_result = searchParticipants($pdo, $participant_name);
}

// Handle the update request
if (isset($_POST['update_payment'])) {
    $user_id = $_POST['registration_id'];
    $event_id = $_POST['event_id'];
    $payment_status = $_POST['payment_status'];
    $table = $_POST['registration_type'];
    $participant_name = $_POST['participant_name_hidden'];

    if (!ctype_digit($event_id)) {
        die("Invalid event ID.");
    }

    if (in_array($table, ['solo', 'duo', 'group'])) {
        $update_table = $table . '_registrations';
        $update_query = "UPDATE $update_table 
                         SET payment_status = :payment_status 
                         WHERE user_id = :user_id AND event_id = :event_id";

        $stmt = $pdo->prepare($update_query);
        $stmt->bindValue(':payment_status', $payment_status, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->bindValue(':event_id', (int)$event_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Re-search after updating to retain the table
    $search_result = searchParticipants($pdo, $participant_name);
}

// Function to search participants
function searchParticipants($pdo, $participant_name) {
    $search_query = "
        SELECT 
            s.user_id AS reg_id,
            s.event_id AS eid,
            u.name AS name1,
            NULL AS name2,
            s.event_name AS ename,
            'solo' AS type,
            s.payment_status
        FROM solo_registrations s 
        LEFT JOIN users u ON s.user_id = u.user_id
        WHERE u.name ILIKE :participant_name

        UNION ALL

        SELECT 
            d.user_id AS reg_id,
            d.event_id AS eid,
            u.name AS name1,
            d.participant_2_name AS name2,
            d.event_name AS ename,
            'duo' AS type,
            d.payment_status
        FROM duo_registrations d 
        LEFT JOIN users u ON d.user_id = u.user_id
        WHERE u.name ILIKE :participant_name OR d.participant_2_name ILIKE :participant_name

        UNION ALL

        SELECT 
            g.user_id AS reg_id,
            g.event_id AS eid,
            u.name AS name1,
            NULL AS name2,
            g.event_name AS ename,
            'group' AS type,
            g.payment_status
        FROM group_registrations g 
        LEFT JOIN users u ON g.user_id = u.user_id
        WHERE u.name ILIKE :participant_name
    ";

    $stmt = $pdo->prepare($search_query);
    $stmt->bindValue(':participant_name', '%' . $participant_name . '%', PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Participants</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="text-center mb-4">Manage Participants</h1>

    <!-- Search Participant -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title text-center">Search Participant</h3>
            <form method="POST" class="row g-3 justify-content-center">
                <div class="col-md-8">
                    <input type="text" name="participant_name" class="form-control" placeholder="Enter participant's name" required 
                           value="<?php echo htmlspecialchars($participant_name); ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" name="search" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($search_result): ?>
        <div class="card shadow-sm mt-5">
            <div class="card-body">
                <h4 class="card-title">Search Results</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Participant Name(s)</th>
                                <th>Event</th>
                                <th>Payment Status</th>
                                <th>Update Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($search_result as $row): ?>
                                <tr>
                                    <td>
                                        <?php
                                            echo htmlspecialchars($row['name1']);
                                            if (!empty($row['name2'])) {
                                                echo " & " . htmlspecialchars($row['name2']);
                                            }   
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['ename']); ?></td>
                                    <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                                    <td>
                                        <form method="POST" class="d-flex align-items-center gap-2">
                                            <input type="hidden" name="registration_id" value="<?php echo htmlspecialchars($row['reg_id']); ?>">
                                            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($row['eid']); ?>">
                                            <input type="hidden" name="registration_type" value="<?php echo htmlspecialchars($row['type']); ?>">
                                            <input type="hidden" name="participant_name_hidden" value="<?php echo htmlspecialchars($participant_name); ?>">

                                            <select name="payment_status" class="form-select form-select-sm" required>
                                                <option value="Paid" <?php echo $row['payment_status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                                <option value="Pending" <?php echo $row['payment_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            </select>
                                            <button type="submit" name="update_payment" class="btn btn-success btn-sm">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>
</div>

</body>

</html>
