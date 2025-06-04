<?php
include 'db.php';
session_start();

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}
$event_id = (int)$_GET['eid'];
$form_data = $_SESSION['update_data'];

// Function to display safely
function display($key) {
    global $form_data;
    return htmlspecialchars($form_data[$key] ?? 'N/A');
}

$event_type = isset($form_data['max']) ? (int)$form_data['max'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Update?</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-4">Already Registered!</h2>
        <p class="mb-4">It looks like you have already registered for this event.</p>
        <p class="mb-4">Do you want to update your registration details with the information you just entered?</p>

        <h3 class="font-semibold mt-2">Your Previously Entered Details:</h3>
        <?php if ($event_type == 1): ?>
            <p class="mb-2"><strong>Name:</strong> <?= display('name') ?></p>
            <p class="mb-2"><strong>Email:</strong> <?= display('email') ?></p>
            <p class="mb-2"><strong>Phone:</strong> <?= display('phone') ?></p>
            <p class="mb-2"><strong>USN:</strong> <?= display('usn') ?></p>
        <?php elseif ($event_type == 2): ?>
            <h3 class="font-semibold mt-2">Member 1:</h3>
            <p class="mb-2"><strong>Name:</strong> <?= display('name1') ?></p>
            <p class="mb-2"><strong>Email:</strong> <?= display('email1') ?></p>
            <p class="mb-2"><strong>USN:</strong> <?= display('usn1') ?></p>
            <p class="mb-2"><strong>Phone:</strong> <?= display('phone') ?></p>

            <h3 class="font-semibold mt-2">Member 2:</h3>
            <p class="mb-2"><strong>Name:</strong> <?= display('name2') ?></p>
            <p class="mb-2"><strong>Email:</strong> <?= display('email2') ?></p>
            <p class="mb-2"><strong>USN:</strong> <?= display('usn2') ?></p>
        <?php elseif ($event_type == 3): ?>
            <p class="mb-2"><strong>Leader Name:</strong> <?= display('leader_name') ?></p>
            <p class="mb-2"><strong>Leader Email:</strong> <?= display('leader_email') ?></p>
            <p class="mb-2"><strong>Team Name:</strong> <?= display('team_name') ?></p>
            <p class="mb-2"><strong>Phone:</strong> <?= display('phone') ?></p>
            <p class="mb-2"><strong>USN:</strong> <?= display('usn') ?></p>
            <p class="mb-2"><strong>Members:</strong> <?= display('members') ?></p>
        <?php endif; ?>
        <p class="mb-2"><strong>College:</strong> <?= display('college') ?></p>
        <p class="mb-2"><strong>Address:</strong> <?= display('address') ?></p>

        <form action="update_process.php" method="POST" class="mt-6">
            <input type="hidden" name="eid" value="<?php echo $event_id; ?>">
            <?php if (isset($_SESSION['update_data']) && is_array($_SESSION['update_data'])): ?>
                <?php foreach ($_SESSION['update_data'] as $key => $value): ?>
                    <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" name="update_submit">Yes, Update My Registration</button>
                <a href="javascript:history.back()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">No, Go Back</a>
            </div>
        </form>
    </div>
</body>
</html> 