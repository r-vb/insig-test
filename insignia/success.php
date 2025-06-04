<?php
$ename = $_GET['ename'] ?? 'the event';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #74ebd5, #acb6e5);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-size: 2em;
            color: #27ae60;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 30px;
        }

        a {
            text-decoration: none;
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #2980b9;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 1.5em;
            }

            p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Registration Successful!</h1>
        <p>You have successfully registered for <strong><?php echo htmlspecialchars($ename); ?></strong>.</p>
        <a href="events.php">🔙 Go back to events</a>
    </div>
</body>
</html>
