<?php
include 'db.php'; // Make sure db.php uses PDO connection

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}
// Insert Event
if (isset($_POST['insert'])) {

    $ENAME = $_POST['ENAME'];
    $description = $_POST['description'];
    $venue = $_POST['venue'];
    $Timing = $_POST['Timing'];
    $DATEE = $_POST['DATEE'];
    $poster_link = $_POST['poster_link'];
    $Register = $_POST['Register'];
    $coordinator1 = $_POST['coordinator1'];
    $coordinator2 = $_POST['coordinator2'];
    $c1phone = $_POST['c1phone'];
    $c2phone = $_POST['c2phone'];
    $rules = $_POST['rules'];
    $bid = $_POST['bid'];
    $cid = $_POST['cid'];
    $price = $_POST['price'];
    $max=$_POST['participants'];
   

    try {
        $sql = "INSERT INTO insig_events 
            (ENAME, description, venue, Timing, DATEE, poster_link, Register, 
            coordinator1, coordinator2, c1phone, c2phone, rules, bid, cid, price,max)
            VALUES 
            (:ENAME, :description, :venue, :Timing, :DATEE, :poster_link, :Register, 
            :coordinator1, :coordinator2, :c1phone, :c2phone, :rules, :bid, :cid, :price, :max)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':ENAME' => $ENAME,
            ':description' => $description,
            ':venue' => $venue,
            ':Timing' => $Timing,
            ':DATEE' => $DATEE,
            ':poster_link' => $poster_link,
            ':Register' => $Register,
            ':coordinator1' => $coordinator1,
            ':coordinator2' => $coordinator2,
            ':c1phone' => $c1phone,
            ':c2phone' => $c2phone,
            ':rules' => $rules,
            ':bid' => $bid,
            ':cid' => $cid,
            ':price' => $price,
            ':max' => $max
        ]);

        echo "<script>alert('Event added successfully!'); window.location.href='adminLanding.php';</script>";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// No need for $conn->close() with PDO — it closes automatically
?>
