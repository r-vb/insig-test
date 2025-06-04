<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action']; // 'update' or 'create'
$email=$_SESSION['user_email'];

// Validate the form inputs
$usn = htmlspecialchars($_POST['usn']);
$phone = htmlspecialchars($_POST['phone']);
$college = htmlspecialchars($_POST['college']);
$confirm_usn = htmlspecialchars($_POST['confirm-usn']);
$name=htmlspecialchars($_POST['name']);


if ($usn !== $confirm_usn) {
    die("USN and Confirm USN do not match.");
}

try {
    if ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE users SET usn = :usn, phone = :phone, college = :college WHERE user_id = :user_id");
        $stmt->execute([':usn' => $usn, ':phone' => $phone, ':college' => $college, ':user_id' => $user_id]);
        echo "Profile updated successfully!";
        header('Location:profile.php');
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (user_id,name,email, usn, phone, college) VALUES (:user_id,:name,:email, :usn, :phone, :college)");
        $stmt->execute([':user_id' => $user_id,':name'=>$name,':email'=>$email,':usn' => $usn, ':phone' => $phone, ':college' => $college]);
        echo "Profile created successfully!";
        header('Location:profile.php');

    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}


if (!empty($_SESSION['redirect_after_profile'])) {
    $redirect_url = $_SESSION['redirect_after_profile'];
    unset($_SESSION['redirect_after_profile']); // Clean up
    header("Location: $redirect_url");
    exit;
} else {
    // Default fallback
    header("Location: landingpage.php");
    exit;
}
?>
