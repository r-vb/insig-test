<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Already Registered</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md text-center">
        <h2 class="text-2xl font-bold mb-6">Oops!</h2>
        <p class="mb-4"><?php echo isset($_SESSION['message']) ? $_SESSION['message'] : "You are already registered for this event."; ?></p>
        <a href="landingpage.php" class="py-3 px-6 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 ease-in-out font-semibold shadow-md">Go to Home</a>
    </div>
</body>
</html>
<?php
unset($_SESSION['message']);
?>
