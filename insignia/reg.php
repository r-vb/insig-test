<?php
session_start();
include 'db.php'; // sets $pdo

if (!isset($_POST['register']) && !isset($_POST['final_submit'])) {
    echo "Access Denied";
    exit;
}

$eid = $_POST['event_id'] ?? null;
$event_type = $_POST['event_type'] ?? $_POST['max'] ?? null;
$event_name = $_POST['ename'] ?? null;

// Show form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['final_submit'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirm Registration</title>
        <link rel="stylesheet" href="reg.css">
    </head>
    <body>
        <div class="form-container">
            <h2>Register for <?= htmlspecialchars($event_name) ?></h2>
            <form method="POST" action="reg.php">
                <input type="hidden" name="event_id" value="<?= htmlspecialchars($eid) ?>">
                <input type="hidden" name="event_type" value="<?= htmlspecialchars($event_type) ?>">
                <input type="hidden" name="final_submit" value="1">
                <input type="hidden" name="ename" value="<?= htmlspecialchars($event_name) ?>">

                <?php if ($event_type == 1): ?>
                    <p>Confirm solo registration.</p>
                <?php elseif ($event_type == 2): ?>
                    <input type="text" name="partner_name" class="input" placeholder="Partner Name" required>
                    <input type="email" name="partner_email" class="input" placeholder="Partner Email" required>
                <?php elseif ($event_type == 3): ?>
                    <textarea name="member_names" class="input" placeholder="Enter group member names, comma-separated" required></textarea>
                <?php else: ?>
                    <div class="alert alert-error">Invalid event type.</div>
                    <?php exit; ?>
                <?php endif; ?>

                <button type="submit" class="submit-btn">Submit Registration</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Final form submit
$user_id = $_SESSION['user_id'] ?? null;

if ($_POST['final_submit'] && $user_id) {
    try {
        if ($event_type == 1) { // solo
            $stmt = $pdo->prepare("INSERT INTO solo_registrations (event_name, user_id, event_id) VALUES (:event_name, :user_id, :event_id)");
            $stmt->execute([
                ':event_name' => $event_name,
                ':user_id' => $user_id,
                ':event_id' => $eid
            ]);
        } elseif ($event_type == 2) { // duo
            $partner_name = $_POST['partner_name'];
            $partner_email = $_POST['partner_email'];
            $stmt = $pdo->prepare("INSERT INTO duo_registrations (event_id, event_name, participant_2_name, user_id) 
                                   VALUES (:event_id, :event_name, :participant_2_name, :user_id)");
            $stmt->execute([
                ':event_id' => $eid,
                ':event_name' => $event_name,
                ':participant_2_name' => $partner_name,
                ':user_id' => $user_id
            ]);
        } elseif ($event_type == 3) { // group
            $raw_names = $_POST['member_names'];
            $names_array = array_map('trim', explode(',', $raw_names));
            $pg_array_literal = '{' . implode(',', array_map(fn($n) => str_replace('"', '\"', $n), $names_array)) . '}';

            $stmt = $pdo->prepare("INSERT INTO group_registrations (event_id, event_name, participants, user_id) 
                                   VALUES (:event_id, :event_name, :participants, :user_id)");
            $stmt->execute([
                ':event_id' => $eid,
                ':event_name' => $event_name,
                ':participants' => $pg_array_literal,
                ':user_id' => $user_id
            ]);
        } else {
            echo "<div class='alert alert-error'>❌ Invalid event type.</div>";
            exit;
        }

        header("Location: success.php?ename=" . urlencode($event_name));
        exit;
    } catch (PDOException $e) {
        echo "<div class='alert alert-error'>❌ Registration failed: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
} else {
    echo "<div class='alert alert-error'>❌ Invalid session or form.</div>";
}
?>
