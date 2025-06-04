<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

// Configure Google Client
$client = new Google_Client();
$client->setClientId('239378230130-n9r7nsp0lrn3icrvroa7g92f0hvsgr8k.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost:3000/insignia/callback.php');
$client->addScope("email");
$client->addScope("profile");

// Capture optional redirect
if (isset($_GET['redirect'])) {
    $_SESSION['redirect_after_login'] = urldecode($_GET['redirect']);
}

// Generate login URL
$loginUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
     body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #b73819 url('img/bg_d.webp') repeat;
}

.login-card {
    padding: 50px 30px;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    text-align: center;
    max-width: 90%;
    width: 100%;
    backdrop-filter: blur(12px);
    background-color: rgba(255, 255, 255, 0.12);
}

h1 {
    font-size: 22px;
    margin-bottom: 25px;
    color: #000;
}

.google-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 24px;
    background-color: #4285F4;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    width: 100%;
    max-width: 300px;
}

.google-btn:hover {
    background-color: #357ae8;
}

.google-icon {
    margin-right: 10px;
    width: 20px;
    height: 20px;
}

/* Responsive tweaks */
@media (max-width: 480px) {
    .login-card {
        transform: scale(1.05);
    }

    h1 {
        font-size: 20px;
    }

    .google-btn {
        font-size: 15px;
        padding: 12px 20px;
    }
}

    </style>
</head>
<body>
    <div class="login-card">
        <h1>Login with Google</h1>
        <a class="google-btn" href="<?= htmlspecialchars($loginUrl) ?>">
            <img class="google-icon" src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" width="20" height="20">
            Login with Google
        </a>
    </div>
</body>
</html>
