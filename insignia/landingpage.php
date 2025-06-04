<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Website with Background Color</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">

  <!-- External CSS --> 
  <link rel="stylesheet" href="landingpage.css" />
  <link rel="stylesheet" href="landingpageMob.css" />
</head>
<body>

 <!-- Header -->
<header>
  <div class="header-flex">
    <div class="sdmlogo">
      <img src="img/sdm.webp" alt="SDM Logo">
    </div>
    <div class="clgname">
      <p>
        SHRI DHARMASTHALA MANJUNATESHWARA COLLEGE OF ENGINEERING AND TECHNOLOGY, DHARWAD
      </p>
    </div>
    <div class="dvh-login">
      <div class="dvh">
        <img src="img/dvh.webp" alt="DVH Logo">
      </div>
      <div class="login-status">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="profile.php" class="login-btn">Profile</a>
        <?php else: ?>
          <a href="login.php" class="login-btn">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>


  <!-- Hero Navigation -->
  <div class="hero-section">
    <nav>
      <ul>
        <li><a href="landingpage.php">Home</a></li>
        <li><a href="events.php">Events</a></li>
        <li><a href="schedule.html">Schedule</a></li>
        <!-- <li><a href="team.html">Team</a></li> -->
       
        <li><a href="contactus.html">Contact Us</a></li>
  
    <li><a href="my_events.php">My events</a></li>


      </ul>
    </nav>
  </div>

  <!-- Visual Elements -->
  <div class="scene">
    <div class="cloud">
      <img src="img/cloud.webp" alt="Cloud">
    </div>
    <div class="round">
      <img src="img/round.svg" alt="Round">
    </div>
    <div class="mahal">
      <img src="img/mahal.webp" alt="Mahal">
    </div>
    <div class="fort">
      <img src="img/fort.webp" alt="Fort">
    </div>
    <div class="temple">
      <div class="auto1">
        <img src="img/auto.webp" alt="Auto Left">
      </div>
      <div class="temple1">
        <img src="img/temple.webp" alt="Temple">
      </div>
      <div class="auto">
        <img src="img/auto.webp" alt="Auto Right">
      </div>
    </div>
  </div>

  <!-- Countdown Timer Section -->
  <div class="timer-section">
    <div class="side-images left-image">
        <!-- <div class="dance-girl">
            <img src="img/danceGirl.webp" alt="">
        </div> -->
        <div class="yaksha-gana">
            <img src="img/yakshagana.webp" alt="">
        </div>
        <!-- <div class="time-elephant">
            <img src="img/timerElephant.webp" alt="">
        </div> -->
    </div>
    <div class="countdown-center">
        <div class="countdown-container">
            <div class="time-box">
                <span id="days">00</span>
                <div class="label">Days</div>
              </div>
              <div class="time-box">
                <span id="hours">00</span>
                <div class="label">Hours</div>
              </div>
              <div class="time-box">
                <span id="minutes">00</span>
                <div class="label">Minutes</div>
              </div>
              <div class="time-box">
                <span id="seconds">00</span>
                <div class="label">Seconds</div>
              </div>
        </div>
        <div class="time-boat">
            <img src="img/boat.webp" alt="">
        </div>
    </div>
    <div class="side-images right-image">
        <!-- <div class="dance-girl">
            <img src="img/danceGirl.webp" alt="">
        </div> -->
        <div class="yaksha-gana">
            <img src="img/yakshagana.webp" alt="">
        </div>
        <!-- <div class="time-elephant">
            <img src="img/timerElephant.webp" alt="">
        </div> -->
    </div>
  </div>

  <!-- Mandala Designs -->
  <div class="mandala">
    <div class="left">
      <img src="img/design.webp" alt="Mandala Left">
    </div>
    <div class="center">
        <img src="img/design.webp" alt="Mandala Center">
    </div>
    <div class="right">
        <img src="img/design.webp" alt="Mandala Right">
    </div>

    <!-- About Section -->
    <div class="info-box">
        <div class="elephant">
            <img class="elephant" src="img/elephant.webp" alt="Elephant Design">
            <!-- <img class="insignia" src="img/insignia 26 1.webp" alt="Elephant Design"> -->
        </div>
        <div class="ele-insignia">
            <img src="img/insignia 26 1.webp" alt="Elephant Design">
        </div>
        <div class="about-info">
          <p>Insignia is the annual techno-cultural fest of SDM College of Engineering and Technology,
             Dharwad. It serves as a vibrant platform where technology meets creativity, bringing 
             together students from various domains to showcase their skills, ideas, and talents.
              With a blend of technical events, cultural showcases, workshops, and competitions, 
              Insignia reflects the spirit of innovation, collaboration, and youthful energy. 
              It’s not just a fest,  it’s a celebration of potential and passion.</p>
        </div>
    </div>
  </div>

  <!-- Singer Section -->
  <div class="spotlight">
    <div class="actor">
        <div class="tree">
            <img src="img/sashwat_bg.webp" alt="">
        </div>
        <div class="kites">
          <img src="img/sashwat.webp" alt="">
      </div>
    </div>
    <div class="descr">
      <img src="img/desc.webp" alt="">
    </div>
    <div class="about-shashwat">
        <div class="shashwat-head">
            <img src="img/shashwat-text.webp" alt="">
        </div>
        <div class="shashwat-desc">
            <p><b>Shashwat Singh</b> is a celebrated Indian playback singer known for hits like 'Haan Main Galat', 'Show Me The Thumka' and 'Wat Wat Wat'. A protégé of A.R. Rahman's KM Music Conservatory, he blends classical finesse with contemporary energy, redefining modern Bollywood music.</p>
        </div>
    </div>
    <div class="leaf">
        <img src="img/leaf.webp" alt="">
    </div>
  </div>

  <!-- Designs Section -->
   <div class="designs">
    <img src="img/designs.webp" alt="">
   </div>

  <!-- Events Section -->
  <div class="events">
    <div class="events-bg">
         <!-- <img src="img/diwali.svg" alt=""> 
         <img src="img/designsbg.webp" alt=""> -->
         <img src="img/design.png" alt="">
         <h1 class="events-title">EVENTS</h1>
    </div>
    <div class="left-doll">
        <img src="img/maledoll.webp" alt="">
    </div>
    <div class="right-doll">
        <img src="img/girl.webp" alt="">
    </div>
    <div class="eventcards">
        <a href="events.php">
            <div class="cultural">
                <picture>
                  <source srcset="img/cultural_mob.webp" media="(max-width: 750px)">
                  <source srcset="img/cultural.webp" media="(min-width: 768px)">
                  <img src="img/cultural.webp" alt="">
                </picture>
            </div>
        </a>
        <a href="events.php">
            <div class="centralized">
                <picture>
                  <source srcset="img/centralized_mob.webp" media="(max-width: 767px)">
                  <source srcset="img/central.webp" media="(min-width: 768px)">
                  <img src="img/central.webp" alt="">
                </picture>
            </div>
        </a>
        <a href="events.php">
            <div class="technical">
                <picture>
                  <source srcset="img/technical_mob.webp" media="(max-width: 767px)">
                  <source srcset="img/tech.webp" media="(min-width: 768px)">
                  <img src="img/tech.webp" alt="">
                </picture>
            </div>
        </a>
    </div>
  </div>
  
   <!-- Designs Section -->
   <div class="memories-mahal">
    <img src="img/Vectors.webp" alt="">
   </div>
   
   <h1 class="wall-of-fame">WALL OF FAME</h1>
   <div class="scroll-container">
    <div class="scroll-track">
      <!-- Repeat your image cards -->
      <div class="image-card"><img src="img/any.webp" alt="Portrait"></div>
      <div class="image-card"><img src="img/pic2.webp" alt="Portrait"></div>
      <div class="image-card"><img src="img/pic3.webp" alt="Portrait"></div>
      <div class="image-card"><img src="img/pic4.webp" alt="Portrait"></div>
      <div class="image-card"><img src="img/pic5.webp" alt="Portrait"></div>

      <!-- Repeat again for seamless looping -->
      <div class="image-card"><img src="img/pic6.webp" alt="Portrait"></div>
      <div class="image-card"><img src="img/pic7.webp" alt="Portrait"></div>
      <div class="image-card"><img src="img/pic8.webp" alt="Portrait"></div>
      <div class="image-card"><img src="img/pic9.webp" alt="Portrait"></div>
      <div class="image-card"><img src="img/pic10.webp" alt="Portrait"></div>
    </div>
  </div>

   <!-- Footer part -->
   <div class="bottom-last">
    <div class="date-location">
        <h2>DATE AND LOCATION</h2>
        <h3>MAY 16-17, 2025</h3>
        <p>SDM College of Engineering & Technology,
            Kalaghatagi Road, Dharwad 580002, Karnataka, India.</p>
        <h2>CO-ORDINATOR DETAILS</h2>
        <p>Shreyas Lalage - 6362637506</p>
        <h2>SOCIAL MEDIA</h2>
        <div class="social-media">
            <a href="https://www.instagram.com/sdmcet_dwd/"><img src="img/Instagram.webp" alt=""></a>
            <a href="https://youtube.com/@insigniaofficial?si=kWpCDdvzLlzcuoBq"><img src="img/YouTube.webp" alt=""></a>
            <a href="https://www.facebook.com/SdmCollegeofEngineering/"><img src="img/Facebook.webp" alt=""></a>
        </div>
    </div>

    <!-- <div class="footer-logo-wrap"> -->
    <div class="insignia-logo">
      <!-- <img
        class="footer-white"
        src="img/insignia white 4.svg"
        alt="Insignia Logo"
      /> -->
      <h1 class="insignia-title">INSIGNIA'25</h1>
      <img
        src="img/insignia 2 8svg.svg"
        alt="Insignia Logo"
      />
    </div>
   </div>

    <footer>
        <p>© 2025 Developed by team <a href="https://sdmcetqwertyio.com" target="_blank">QWERTY.I/O</a>.</p>
    </footer>
    
  <!-- JavaScript File -->
  <script src="landingpage.js"></script>
</body>
</html>
