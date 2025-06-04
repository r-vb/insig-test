<?php
session_start();
// Include the connection setup from db.php
include 'db.php';  // This will use the $conn already created in db.php

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Insert or Update Event
if (isset($_POST['submit'])) {
    $EID = $_POST['EID'];
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
    $max = $_POST['participants'];

    try {
        // Ensure the variables are null if empty
        $bid = ($bid === '') ? null : $bid;
        $cid = ($cid === '') ? null : $cid;
        $price = ($price === '') ? null : $price;
        $EID = ($EID === '') ? null : $EID;

        if (!empty($EID)) {
            // Update existing event
            $sql = "UPDATE insig_events SET 
                        ENAME = :ENAME, description = :description, venue = :venue, 
                        Timing = :Timing, DATEE = :DATEE, poster_link = :poster_link, 
                        Register = :Register, coordinator1 = :coordinator1, coordinator2 = :coordinator2, 
                        c1phone = :c1phone, c2phone = :c2phone, rules = :rules, 
                        bid = :bid, cid = :cid, price = :price, max = :max
                    WHERE EID = :EID";

            $stmt = $pdo->prepare($sql);
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
                ':max' => $max,
                ':EID' => $EID
            ]);
        } else {
            // Insert new event
            $sql = "INSERT INTO insig_events 
                    (ENAME, description, venue, Timing, DATEE, poster_link, Register, 
                     coordinator1, coordinator2, c1phone, c2phone, rules, bid, cid, price, max) 
                    VALUES 
                    (:ENAME, :description, :venue, :Timing, :DATEE, :poster_link, :Register, 
                     :coordinator1, :coordinator2, :c1phone, :c2phone, :rules, :bid, :cid, :price, :max)";
            
            $stmt = $pdo->prepare($sql);
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
        }

        // After successful update/insert, redirect
        header("Location: admin.php");
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Delete Event
if (isset($_GET['delete'])) {
    $EID = $_GET['delete'];

    try {
        $sql = "DELETE FROM insig_events WHERE EID = :EID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':EID' => $EID]);

        header("Location: admin.php");
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// No need to explicitly close PDO connection, it's managed by db.php
?>
