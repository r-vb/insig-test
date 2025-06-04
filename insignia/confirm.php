<?php
session_start();
require 'db.php';
// Debug session info


// Safely access form data
if (isset($_SESSION['form_data'])) {
    $form_data = $_SESSION['form_data'];
} else {
    echo "No form data found in session. Please complete the registration form first.";
    exit;
}

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
    <title>Confirm Your Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        // Moved script to the head section
        function disableButton(button) {
            button.disabled = true;
            button.innerText = 'Submitting...';
            // Important:  Also disable other form elements if needed.
            // var form = button.form; // Get the form
            // if (form) {
            //   for (var i = 0; i < form.elements.length; i++) {
            //     if (form.elements[i].type != 'submit') { // Don't disable the submit button itself
            //       form.elements[i].disabled = true;
            //     }
            //   }
            // }
        }
    </script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Please Confirm Your Details</h2>

        <?php if ($event_type == 1): ?>
            <p class="mb-2"><strong class="font-semibold">Name:</strong> <?= display('name') ?></p>
            <p class="mb-2"><strong class="font-semibold">Email:</strong> <?= display('email') ?></p>
            <p class="mb-2"><strong class="font-semibold">Phone:</strong> <?= display('phone') ?></p>
            <p class="mb-2"><strong class="font-semibold">USN:</strong> <?= display('usn') ?></p>
        <?php elseif ($event_type == 2): ?>
            <h3 class="font-semibold mt-2">Member 1:</h3>
            <p class="mb-2"><strong class="font-semibold">Name:</strong> <?= display('name1') ?></p>
            <p class="mb-2"><strong class="font-semibold">Email:</strong> <?= display('email1') ?></p>
            <p class="mb-2"><strong class="font-semibold">USN:</strong> <?= display('usn1') ?></p>
            <p class="mb-2"><strong class="font-semibold">Phone:</strong> <?= display('phone') ?></p>

            <h3 class="font-semibold mt-2">Member 2:</h3>
            <p class="mb-2"><strong class="font-semibold">Name:</strong> <?= display('name2') ?></p>
            <p class="mb-2"><strong class="font-semibold">Email:</strong> <?= display('email2') ?></p>
            <p class="mb-2"><strong class="font-semibold">USN:</strong> <?= display('usn2') ?></p>
        <?php elseif ($event_type == 3): ?>
            <p class="mb-2"><strong class="font-semibold">Leader Name:</strong> <?= display('leader_name') ?></p>
            <p class="mb-2"><strong class="font-semibold">Leader Email:</strong> <?= display('leader_email') ?></p>
            <p class="mb-2"><strong class="font-semibold">Team Name:</strong> <?= display('team_name') ?></p>
            <p class="mb-2"><strong class="font-semibold">Phone:</strong> <?= display('phone') ?></p>
            <p class="mb-2"><strong class="font-semibold">USN:</strong> <?= display('usn') ?></p>
            <p class="mb-2"><strong class="font-semibold">Members:</strong> <?= display('members') ?></p>
        <?php else: ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                Invalid event type.
            </div>
        <?php endif; ?>

        <p class="mb-2"><strong class="font-semibold">College:</strong> <?= display('college') ?></p>
        <p class="mb-2"><strong class="font-semibold">Address:</strong> <?= display('address') ?></p>

        <?php if ($event_type >= 1 && $event_type <= 3): ?>
            <form action="success.php" method="POST" class="mt-6" id="confirmForm">
                <input type="hidden" name="confirm" value="1">
                <?php if (isset($_SESSION['form_data']) && is_array($_SESSION['form_data'])): ?>
                    <?php foreach ($_SESSION['form_data'] as $key => $value): ?>
                        <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="flex justify-center">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-md font-semibold transition duration-300 ease-in-out shadow-md"
                            id="confirmSubmitButton"
                            >
                        Verify & Confirm
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
