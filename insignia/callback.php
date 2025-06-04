<?php
session_start();
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('239378230130-n9r7nsp0lrn3icrvroa7g92f0hvsgr8k.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost:3000/insignia/callback.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['access_token'])) {
        $_SESSION['access_token'] = $token['access_token'];
        $client->setAccessToken($token['access_token']);

        $oauth = new Google_Service_Oauth2($client);
        $userInfo = $oauth->userinfo->get();

        $_SESSION['user_id'] = $userInfo->id;
        $_SESSION['user_name'] = $userInfo->name;
        $_SESSION['user_email'] = $userInfo->email;

        // ✅ Redirect to stored redirect page if available
        // After successful login
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect = urldecode($_SESSION['redirect_after_login']);  // Correctly decoding
            unset($_SESSION['redirect_after_login']); // Optional: clear it after use
            header("Location: $redirect");
        } else {
            header('Location: profile.php');
        }
        exit();
    } else {
        echo 'Error: Could not fetch the access token!<br>';
        echo '<pre>';
        print_r($token); // Log or display the entire token response
        echo '</pre>';
    }
} else {
    echo 'Error: No code received!';
}
?>

