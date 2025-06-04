<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}




$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Fetch profile if it exists
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching profile: " . $e->getMessage());
}

// Pre-fill values
$usn = $profile['usn'] ?? '';
$phone = $profile['phone'] ?? '';
$college = $profile['college'] ?? '';
$action = $profile ? 'update' : 'create';
$button_text = $profile ? 'Update Profile' : 'Save Profile';
?>
<?php if (!empty($_SESSION['profile_incomplete'])): ?>
    <p style="color: yellow; background-color: red; padding: 10px; border-radius: 5px; text-align: center;">
        Please complete your profile before registering for an event.
    </p>
    <?php unset($_SESSION['profile_incomplete']); ?>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile Page</title>
  <style>
 body {
      font-family: 'Lato', sans-serif;
      background: #b73819 url('img/bg_d.webp') repeat;
      margin: 0;
      padding: 0;
      color: #ffffff;
    }

    .profile-container {
      width: 100%;
      max-width: 600px;
      margin: 50px auto;
      padding: 20px;
      border-radius: 8px;
      background-color: rgba(0, 0, 0, 0.3);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    h1 {
      text-align: center;
      font-size: 28px;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      font-size: 16px;
      display: block;
      margin-bottom: 8px;
    }

    input {
      width: 100%;
      padding: 12px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
      color: #000;
      background-color: #fff;
      box-sizing: border-box;
    }

    input:focus {
      border-color: #007BFF;
      outline: none;
    }

    .form-group input[type="tel"] {
      font-family: "Courier New", Courier, monospace;
    }

    button.submit-btn {
      width: 100%;
      padding: 15px;
      background-color: #007BFF;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button.submit-btn:hover {
      background-color: #0056b3;
    }

    .navigation-links {
      margin-top: 30px;
      text-align: center;
    }

    .navigation-links a {
      display: inline-block;
      margin: 10px;
      padding: 10px 20px;
      background-color: #2ecc71;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .navigation-links a:hover {
      background-color: #27ae60;
    }

    .logout-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #fff;
    }  </style>
</head>
<body>
  <div class="profile-container">
    <h1>Profile Information</h1>
    <form action="submit_profile.php" method="POST">
      <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
      <input type="hidden" name="action" value="<?= $action ?>">

      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user_name) ?>" readonly>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user_email) ?>" readonly>
      </div>

      <div class="form-group">
        <label for="usn">USN</label>
        <input type="text" id="usn" name="usn" required placeholder="Enter your USN" value="<?= htmlspecialchars($usn) ?>">
      </div>

      <div class="form-group">
        <label for="confirm-usn">Confirm USN</label>
        <input type="text" id="confirm-usn" name="confirm-usn" required placeholder="Confirm your USN" value="<?= htmlspecialchars($usn) ?>">
      </div>

      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" required placeholder="Enter your phone number" pattern="[0-9]{10}" value="<?= htmlspecialchars($phone) ?>">
      </div>

      <div class="form-group">
        <label for="college">College</label>
        <input type="text" id="college" name="college" required placeholder="College" value="<?= htmlspecialchars($college) ?>">
      </div>

      <button type="submit" class="submit-btn"><?= $button_text ?></button>
    </form>

    <div class="navigation-links">
      <a href="landingpage.php">Go to Home</a>
      
    </div>

    <a class="logout-link" href="logout.php">Logout</a>
  </div>
</body>
</html>

