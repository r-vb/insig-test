<?php
include 'db.php';

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Fetch events by category
function fetchEvents($conn, $category) {
    $query = "SELECT ename,eid,cid FROM insig_events WHERE cid = $1";
    $result = pg_query_params($conn, $query, array($category));
    $events = [];
    while ($row = pg_fetch_assoc($result)) {
        $events[] = $row;
    }
    return $events;
}

$culturalEvents = fetchEvents($conn, 1);
$centralizedEvents = fetchEvents($conn, 3);
$technicalEvents = fetchEvents($conn, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Events Page</title>
  <link rel="stylesheet" href="events.css" />
</head>
<body>
 
  <!-- Side decorations -->
  <img src="img/Group 57 (1).svg" alt="Left decoration" class="side-decoration left-decoration" />
  <img src="img/Group 48095535 (1).svg" alt="Right decoration" class="side-decoration right-decoration" />

  <img src="img/EVENTS.svg" alt="events" class="section-img" />

  <main id="eventContainer">

    <!-- Cultural Section -->
    <section class="event-section">
      <img src="img/dance.webp" alt="Cultural" class="section-img" />
      <img src="img/CULTURAL copy.svg" alt="Cultural" class="section-img" />
      <div class="event-buttons">
      <?php foreach ($culturalEvents as $event): ?>
  <a href="details.php?event=<?= urlencode($event['ename']) ?>&eid=<?= $event['eid'] ?>&cid=<?= $event['cid'] ?>">
    <?= htmlspecialchars($event['ename']) ?>
  </a>
<?php endforeach; ?>
      </div>
    </section>

    <div class="custom-divider">
      <img src="img/Group 48095540.svg" alt="Divider" />
    </div>

    <!-- Centralized Section -->
    <section class="event-section">
      <img src="img/Centralized.webp" alt="Centralized" class="section-img" />
      <img src="img/CENTRALIZED.svg" alt="Centralized" class="section-img" />
      <div class="event-buttons">
        <?php foreach ($centralizedEvents as $event): ?>
  <a href="details.php?event=<?= urlencode($event['ename']) ?>&eid=<?= $event['eid'] ?>&cid=<?= $event['cid'] ?>">
    <?= htmlspecialchars($event['ename']) ?>
  </a>
<?php endforeach; ?>
      </div>
    </section>

    <div class="custom-divider">
      <img src="img/Group 48095540.svg" alt="Divider" />
    </div>

    <!-- Technical Section -->
    <section class="event-section">
      <img src="img/technical.png" alt="Technical" class="section-img" />
      <img src="img/Group 67.svg" alt="Techncal" class="section-img" />
      <div class="event-buttons">
       
        <a onclick="goTOCSE()">CSE</a>
        <a onclick="gotoISE()">ISE</a>
        <a href="branches.html?branch=AIML">AIML</a>
        <a onclick="goTOECE()">E&C</a>
        <a href="branches.html?branch=CIVIL">CIVIL</a>
        <a onclick=" goToMech()">Mechanical</a>
      </div>
    </section>

  </main>
  
  
<div class="backto">
  <a href="landingpage.php" class="b2l">Back to landing</a>
</div>
  <script>
    function goTOCSE(){
    window.location.href='event.php?eid='+ 1 + '&bid=' + 1 + '&cid=' + 2;
    }

    function goTOECE(){
      window.location.href='event.php?eid='+ 4 + '&bid='+ 5 + '&cid=' + 2;
    }

    function goToMech(){
      window.location.href='event.php?eid='+ 2 + '&bid='+ 2 + '&cid=' + 2;

    }

    function gotoISE(){
      window.location.href='event.php?&bid='+ 8 + '&cid=' + 2;

    }

    function GoLand(){
            window.location.href='landingpage.php';

    }
  </script>

  <footer>
    <img src="img/Layer_1.png" alt="Layer 1" class="footer-img" />
  </footer>
</body>
</html>
