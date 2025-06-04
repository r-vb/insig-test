<?php
session_start();
include 'db.php'; // Include the PDO connection
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];


// Fetch profile from users table
$stmt = $pdo->prepare("SELECT usn, phone, college FROM users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if profile is incomplete
if (empty($profile['usn']) || empty($profile['phone']) || empty($profile['college'])) {
    $_SESSION['profile_incomplete'] = true;
    $_SESSION['redirect_after_profile'] = $_SERVER['REQUEST_URI'];
    header("Location: profile.php");
    exit;
}
$EID = isset($_GET['eid']) ? intval($_GET['eid']) : 0;
$BID = isset($_GET['bid']) ? intval($_GET['bid']) : 0;
$CID = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
$dir = isset($_GET['dir']) ? htmlspecialchars($_GET['dir']) : 'fade';
$ename=isset($_GET['ename']) ?htmlspecialchars($_GET['ename']) :0;



try {
    $event = null;

    if ($EID > 0) {
        // Use PDO here
        $stmt = $pdo->prepare("SELECT eid, ename, description, venue, timing, datee, poster_link, rules, price, register, coordinator1, coordinator2, c1phone, c2phone, bid, cid, max FROM insig_events WHERE eid = :eid");
        $stmt->bindParam(':eid', $EID, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($event) {
        $rules = !empty($event['rules']) ? preg_split('/\r\n|\r|\n/', $event['rules']) : [];
        $max = htmlspecialchars($event['max']);
        $ename=htmlspecialchars($event['ename']);
        $event_id=htmlspecialchars($event['eid']);

        // Use PDO here
        $stmt_eid = $pdo->prepare("SELECT eid FROM insig_events WHERE bid = :bid ORDER BY eid ASC");
        $stmt_eid->bindParam(':bid', $BID, PDO::PARAM_INT);
        $stmt_eid->execute();
        $event_ids = array_column($stmt_eid->fetchAll(PDO::FETCH_ASSOC), 'eid');

        $current_index = array_search($EID, $event_ids);
        $prev_event = $event_ids[$current_index - 1] ?? null;
        $next_event = $event_ids[$current_index + 1] ?? null;
        
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}


$is_registered = false;

if (!empty($event)) {
    $max = intval($event['max']); // event_type: 1 (solo), 2 (duo), 3 (group)

    if ($max === 1) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM solo_registrations WHERE user_id = :user_id AND event_id = :event_id");
    } elseif ($max === 2) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM duo_registrations WHERE user_id = :user_id AND event_id = :event_id");
    } elseif ($max === 3) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM group_registrations WHERE user_id = :user_id AND event_id = :event_id");
    } else {
        $stmt = null; // no valid max type
    }

    if ($stmt) {
        $stmt->execute(['user_id' => $user_id, 'event_id' => $EID]);
        $count = $stmt->fetchColumn();
        $is_registered = ($count > 0);
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <link rel="stylesheet" href="details.css">
    <style>
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(80px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .card-inner.animate-slide { animation: slideInRight 0.6s ease-in-out; }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const card = document.querySelector(".card-inner");
            if (card) card.classList.add("animate-slide");
        });
    </script>
</head>
<body>
<div class="background"></div>

<?php if (!empty($event)): ?>
    <div class="card-container">
        <div class="card-inner animate-<?php echo htmlspecialchars($dir); ?>">
            <div class="left-image">
                <img src="<?php echo htmlspecialchars($event['poster_link']); ?>" alt="Event Image" class="left-image">
                <div class="image-coordinators">
                    <p class="font-semibold">Coordinators:</p>
                    <p><?php echo htmlspecialchars($event['coordinator1']); ?> - <?php echo htmlspecialchars($event['c1phone']); ?></p>
                    <p><?php echo htmlspecialchars($event['coordinator2']); ?> - <?php echo htmlspecialchars($event['c2phone']); ?></p>
                </div>
            </div>
            <div class="details">
                <h1><?php echo htmlspecialchars($event['ename']); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                <p><strong>Time:</strong> <?php echo htmlspecialchars($event['timing']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($event['datee']); ?></p>

                <?php if (!empty($rules)): ?>
                    <div class="rules">
                        <p class="font-semibold">Rules:</p>
                        <ul>
                            <?php foreach ($rules as $rule): ?>
                                <li><?php echo htmlspecialchars(trim($rule)); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <p class="price">RS - <?php echo htmlspecialchars($event['price'] ?? '200'); ?> /-</p>

                <div class="text-center">
<?php if ($is_registered): ?>
    <button type="button" disabled style="background-color: gray; cursor: not-allowed;">Registered</button>
<?php else: ?>
    <form action="reg.php" method="POST">
        <input type="hidden" name="event_id" value="<?= $event_id ?>">
        <input type="hidden" name="max" value="<?= $max ?>">
        <input type="hidden" name="ename" value="<?= $ename ?>">
        <button type="submit" name="register">Register</button>
    </form>
<?php endif; ?>


                </div>  
            </div>
        </div>

        <div class="navigation-buttons">
            <?php if ($prev_event): ?>
                <a href="details.php?eid=<?php echo $prev_event; ?>&bid=<?php echo $BID; ?>&cid=<?php echo $CID; ?>&dir=right">
                    <button class="nav-btn">Previous</button>
                </a>
            <?php else: ?><span></span><?php endif; ?>

            <?php if ($next_event): ?>
                <a href="details.php?eid=<?php echo $next_event; ?>&bid=<?php echo $BID; ?>&cid=<?php echo $CID; ?>&dir=left">
                    <button class="nav-btn">Next</button>
                </a>
            <?php endif; ?>
        </div>

        <div class="backto">
            <a href="events.php" class="back-to-branches">Back to Branches</a>
        </div>
    </div>

<?php else: ?>
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-4">No event found</h2>
        <p>The event you're looking for doesn't exist or has been removed.</p>
    </div>
<?php endif; ?>
</body>
</html>

<?php
$conn = null; // close connection
?>
