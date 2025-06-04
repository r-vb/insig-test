<?php
include 'db.php'; // This should define your $pdo using PDO

if (!$pdo) {
    die("Connection failed.");
}

// Get branch ID from the URL
$bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;

// Fetch branch name
try {
    $branch_sql = "SELECT bname FROM insig_branch WHERE bid = :bid";
    $stmt = $pdo->prepare($branch_sql);
    $stmt->bindParam(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    $branch_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $branch_name = ($branch_data) ? $branch_data['bname'] : 'Unknown';
} catch (PDOException $e) {
    die("Error fetching branch name: " . $e->getMessage());
}

// Fetch events for the selected branch
try {
    $event_sql = "SELECT eid, ename, description, venue, timing, datee, poster_link, bid, cid FROM insig_events WHERE bid = :bid";
    $stmt = $pdo->prepare($event_sql);
    $stmt->bindParam(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    $event_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching events: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events in <?php echo htmlspecialchars($branch_name); ?></title>
    <link rel="stylesheet" href="event.css">
</head>
<body class="body">

    <h1>Events in <?php echo htmlspecialchars($branch_name); ?></h1>

    <div class="container">
    <?php
    if (count($event_result) > 0) {
        foreach ($event_result as $event) {
            $eid = htmlspecialchars($event['eid'], ENT_QUOTES, 'UTF-8');
            $bid = htmlspecialchars($event['bid'], ENT_QUOTES, 'UTF-8');
            $cid = htmlspecialchars($event['cid'], ENT_QUOTES, 'UTF-8');
            $ename = htmlspecialchars($event['ename'], ENT_QUOTES, 'UTF-8');
            $poster_link = htmlspecialchars($event['poster_link'], ENT_QUOTES, 'UTF-8');

            echo "
                <div class='card' onclick='showDetails($eid, $bid, $cid)'>
                    <img src='$poster_link' alt='$ename' width='100'>
                    <h3>$ename</h3>
                </div>
            ";
        }
    } else {
        echo "<p>No events available for this branch.</p>";
    }
    ?>
    </div>
    <div class="backto">
        <a href="events.php">Back to Branches</a>
    </div>

    <script>
        function showDetails(eid, bid, cid) {
            window.location.href = 'details.php?eid=' + eid + '&bid=' + bid + '&cid=' + cid;
        }
    </script>
</body>
</html>

<?php
// Close the PDO connection
$pdo = null;
?>
