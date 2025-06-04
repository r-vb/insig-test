<?php
session_start();
require_once 'db.php'; // contains your $pdo connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Function to fetch events from a table
function fetchEvents($pdo, $user_id, $table, $event_type = '') {
    $sql = "SELECT insig_events.ename AS name,
                   insig_events.poster_link,
                   insig_events.venue,
                   insig_events.timing,
                   insig_events.datee 
            FROM $table
            JOIN insig_events ON $table.event_id = insig_events.eid
            WHERE $table.user_id = :user_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);

    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($event_type) {
        foreach ($events as &$event) {
            $event['type'] = $event_type;
        }
    }

    return $events;
}

// Fetch solo, duo, and group events
$solo_events = fetchEvents($pdo, $user_id, 'solo_registrations', 'Solo');
$duo_events = fetchEvents($pdo, $user_id, 'duo_registrations', 'Duo');
$group_events = fetchEvents($pdo, $user_id, 'group_registrations', 'Group');
$events = array_merge($solo_events, $duo_events, $group_events);
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Registered Events</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #b73819 url('img/bg_d.webp') repeat;
            margin: 0;
            padding: 0;
            padding-top: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

```
    h1 {
        font-size: 2.5em;
        color: #fff;
        margin-bottom: 30px;
        font-weight: bold;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
    }

    .events-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        padding: 20px;
    }

    .event-card {
        width: 260px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(12px);
        color: #fff;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
    }

    .event-card img {
        width: 100%;
        backdrop-filter: blur;
        height: 180px;
        object-fit: contain;
        border-radius: 12px;
        margin-bottom: 12px;
      
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .event-title {
        font-size: 1.3em;
        font-weight: 600;
        text-align: center;
        margin-bottom: 4px;
    }

    .event-description {
        font-size: 0.95em;
        font-style: italic;
        color: #f9f9f9;
        text-align: center;
        margin-bottom: 12px;
    }

    .event-type {
        font-size: 1em;
        background: #ff8c42;
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        margin-bottom: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
    }

    .event-meta {
        font-size: 0.95em;
        backdrop-filter: blur;
        margin: 5px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .event-meta i {
        color: #ffe9a7;
        min-width: 16px;
    }

    .no-events {
        font-size: 1.4em;
        color: #eee;
        margin-top: 30px;
    }

    @media (max-width: 768px) {
        .event-card {
            width: 90%;
        }

        h1 {
            font-size: 2em;
        }
    }
</style>
```

</head>
<body>
    <h1>My Registered Events</h1>
    <div class="events-container">
        <?php if (empty($events)): ?>
            <p class="no-events">You haven't registered for any events yet.</p>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <img src="<?= htmlspecialchars($event['poster_link']) ?>" alt="<?= htmlspecialchars($event['name']) ?>">
                    <div class="event-title"><?= htmlspecialchars($event['name']) ?></div>
                    <div class="event-type"><?= htmlspecialchars($event['type']) ?></div>
                    <div class="event-meta"><i class="fa-solid fa-clock"></i> <?= htmlspecialchars($event['timing']) ?></div>
                    <div class="event-meta"><i class="fa-solid fa-calendar-day"></i> <?= htmlspecialchars($event['datee']) ?></div>
                    <div class="event-meta"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($event['venue']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>  