<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

if (isset($_POST['update'])) {
    $ENAME = $_POST['ENAME'];

    // Fetch event by name
    $result = pg_query_params($conn, "SELECT * FROM insig_events WHERE ENAME = $1", array($ENAME));
    $event = pg_fetch_assoc($result);

    if (!$event) {
        echo "<script>alert('Event not found! Please check the event name.'); window.location.href='adminLanding.php';</script>";
        exit;
    }

    // Use form data or fallback to existing
    $description = !empty($_POST['description']) ? $_POST['description'] : $event['description'];
    $venue = !empty($_POST['venue']) ? $_POST['venue'] : $event['venue'];
    $Timing = !empty($_POST['Timing']) ? $_POST['Timing'] : $event['timing'];
    $DATEE = !empty($_POST['DATEE']) ? $_POST['DATEE'] : $event['datee'];
    $poster_link = !empty($_POST['poster_link']) ? $_POST['poster_link'] : $event['poster_link'];
    $Register = !empty($_POST['Register']) ? $_POST['Register'] : $event['register'];
    $coordinator1 = !empty($_POST['coordinator1']) ? $_POST['coordinator1'] : $event['coordinator1'];
    $coordinator2 = !empty($_POST['coordinator2']) ? $_POST['coordinator2'] : $event['coordinator2'];
    $c1phone = !empty($_POST['c1phone']) ? $_POST['c1phone'] : $event['c1phone'];
    $c2phone = !empty($_POST['c2phone']) ? $_POST['c2phone'] : $event['c2phone'];
    $rules = !empty($_POST['rules']) ? $_POST['rules'] : $event['rules'];
    $bid = !empty($_POST['bid']) ? $_POST['bid'] : $event['bid'];
    $cid = !empty($_POST['cid']) ? $_POST['cid'] : $event['cid'];
    $price = !empty($_POST['price']) ? $_POST['price'] : $event['price'];
    $max= !empty($_POST['participants']) ? $_POST['participants'] : $event['max'];
    
    $sql = "UPDATE insig_events 
            SET description = $1,
                venue = $2,
                Timing = $3,
                DATEE = $4,
                poster_link = $5,
                Register = $6,
                coordinator1 = $7,
                coordinator2 = $8,
                c1phone = $9,
                c2phone = $10,
                rules = $11,
                bid = $12,
                cid = $13,
                price = $14,
                max=$15

            WHERE EID = $16";

    $params = array(
        $description, $venue, $Timing, $DATEE, $poster_link, $Register,
        $coordinator1, $coordinator2, $c1phone, $c2phone, $rules,
        $bid, $cid, $price, $max,$event['eid']
    );

    $result = pg_query_params($conn, $sql, $params);

    if ($result) {
        echo "<script>alert('Event updated successfully!'); window.location.href='adminLanding.php';</script>";
    } else {
        echo "Error: Could not update event. " . pg_last_error($conn);
    }
}
?>
