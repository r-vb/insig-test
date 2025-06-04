<?php
session_start();
require 'db.php'; // Include your database connection file

// Get username and password from POST
$username = $_POST['username'];
$password = $_POST['password'];

try {
    // Prepare the SQL query with a placeholder for the username
$stmt = $pdo->prepare("SELECT * FROM admin WHERE username = :username");
    
    // Bind the username parameter to the prepared statement
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    
    // Execute the query
    $stmt->execute();
    
    // Check if any rows are returned
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Verify the password using password_verify()
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header("Location: adminLanding.php");
            exit();
        } else {
            // Incorrect password
            $_SESSION['login_error'] = "Invalid credentials.";
            header("Location: admin_login.php");
            exit();
        }
    } else {
        // User not found
        $_SESSION['login_error'] = "User not found.";
        header("Location: admin_login.php");
        exit();
    }
} catch (PDOException $e) {
    // In case of a database connection error
    $_SESSION['login_error'] = "Error: " . $e->getMessage();
    header("Location: admin_login.php");
    exit();
}
?>
