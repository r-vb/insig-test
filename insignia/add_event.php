<?php
// db.php - Plain PostgreSQL connection using PDO
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

try {
    // Fetch all events
    $events_sql = "SELECT * FROM insig_events";
    $stmt = $pdo->query($events_sql);  // Using PDO's query method to fetch results
    $events_result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all events as an associative array

    // Fetch branches
    $branch_sql = "SELECT * FROM insig_branch";
    $stmt = $pdo->query($branch_sql);  // Using PDO's query method to fetch results
    $branch_result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all branches as an associative array

    // Fetch categories
    $category_sql = "SELECT * FROM insig_categories";
    $stmt = $pdo->query($category_sql);  // Using PDO's query method to fetch results
    $category_result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all categories as an associative array

    // Store branch names in an array
    $branches = array();
    foreach ($branch_result as $branch) {
        $branches[$branch['bid']] = $branch['bname'];  // Store branch data
    }

    // Store category names in an array
    $categories = array();
    foreach ($category_result as $category) {
        $categories[$category['cid']] = $category['cname'];  // Store category data
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Handle Delete action
if (isset($_GET['delete'])) {
    try {
        $EID = (int) $_GET['delete'];  // Sanitize input and ensure it is an integer
        if ($EID > 0) {
            // Prepare the DELETE SQL query
            $delete_sql = "DELETE FROM insig_events WHERE EID = :EID";
            $stmt = $pdo->prepare($delete_sql);
            $stmt->bindParam(':EID', $EID, PDO::PARAM_INT);
            $result = $stmt->execute();  // Execute the query

            // Redirect after deleting the event
            if ($result) {
                header("Location: view_table.php");  // Redirect to the view table page
                exit();
            } else {
                echo "Error deleting event.";
            }
        } else {
            echo "Invalid event ID.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
