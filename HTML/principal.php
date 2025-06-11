<!DOCTYPE html>
<html lang="en">

<?php
require_once '../php/eventController.php';
require_once '../php/gameController.php';
$controller = new EventController();
$game = new GameController();
$eventos = $controller->returnrecenteventsprincipal();
unset($_SESSION['error']);

$bestgame = $game->getbestgame();
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NextPlay - Home</title>
  <link rel="icon" href="../imagenes/logo.png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../CSS/principal.css">
  <link rel="stylesheet" href="../Layout/layout.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../Layout/include.js"></script>
  <script src="../Layout/auth.js"></script>
  <link rel="stylesheet" href="../CSS/auth.css">
</head>

<body>
  <header>
    <div id="navbar"></div>
    <div id="loginwindow"></div>
    <div id="registerwindow"></div>
  </header>
  <main>
    <div id="main-auth" class="container">
      <section class="carousel-container">
        <div id="gameCarousel" class="carousel slide carousel-inner">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="../games/nte.jpg" class="carousel-img" alt="Neverness to Everness">
              <div class="carousel-caption">
                <h5>Neverness to Everness</h5>
                <p>Coming soon in 2025!</p>
                <a href="#" class="btn">Explore Game</a>
              </div>
            </div>
            <div class="carousel-item">
              <img src="../games/csgo.jpg" class="carousel-img" alt="Counter-Strike">
              <div class="carousel-caption">
                <h5>Counter-Strike</h5>
                <p>Global multiplayer battles!</p>
                <a href="#" class="btn">Explore Game</a>
              </div>
            </div>
            <div class="carousel-item">
              <img src="../games/wuwa.jpg" class="carousel-img" alt="Wuthering Waves">
              <div class="carousel-caption">
                <h5>Wuthering Waves</h5>
                <p>Open-world adventure!</p>
                <a href="#" class="btn">Explore Game</a>
              </div>
            </div>
          </div>
          <button class="evt-carousel-prev" aria-label="Evento anterior"><i class="fas fa-chevron-left"></i></button>
          <button class="evt-carousel-next" aria-label="Evento siguiente"><i
              class="fas fa-chevron-right"></i></button>
        </div>
      </section>

      <section id="game-of-month" class="mb-5">
        <h2 class="neon-text text-center mb-4">Game of the Month</h2>
        <div class="row">
          <div class="col-md-12">
            <div class="card featured">
              <img src="<?= htmlspecialchars($bestgame['image']) ?>" class="fixed-height-media" alt="Elden Ring">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($bestgame['nombre']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($bestgame['descripcion']) ?></p>
                <p class="card-text">Partipants: <?= htmlspecialchars($bestgame['total_participantes']) ?></p>
                <a href="<?= htmlspecialchars($bestgame['link']) ?>" class="btn">Explore Game</a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="popular-games">
        <h2 class="neon-text text-center mb-4">Popular Games</h2>
        <div class="games-carousel">
          <div class="game-card">
            <span class="new-label">New</span>
            <img src="../games/roblox.jpg" alt="Roblox">
            <div class="game-card-body">
              <h5 class="card-title">Roblox</h5>
              <p class="card-text">Create and play in a limitless universe!</p>
              <div class="game-stats">
                <span>Players: 50M+</span>
                <span>Genre: Sandbox</span>
              </div>
              <a href="#" class="btn">Play Now</a>
            </div>
          </div>
          <div class="game-card">
            <img src="../games/minecraft.jpg" alt="Minecraft">
            <div class="game-card-body">
              <h5 class="card-title">Minecraft</h5>
              <p class="card-text">Build and explore endless worlds!</p>
              <div class="game-stats">
                <span>Players: 140M+</span>
                <span>Genre: Survival</span>
              </div>
              <a href="#" class="btn">Play Now</a>
            </div>
          </div>
          <div class="game-card">
            <img src="../games/genshin.jpg" alt="Genshin Impact">
            <div class="game-card-body">
              <h5 class="card-title">Genshin Impact</h5>
              <p class="card-text">Adventure in a vibrant open world!</p>
              <div class="game-stats">
                <span>Players: 60M+</span>
                <span>Genre: RPG</span>
              </div>
              <a href="#" class="btn">Play Now</a>
            </div>
          </div>
          <div class="game-card">
            <img src="../games/cyberpunk.jpg" alt="Cyberpunk 2077">
            <div class="game-card-body">
              <h5 class="card-title">Cyberpunk 2077</h5>
              <p class="card-text">Live in a futuristic dystopia!</p>
              <div class="game-stats">
                <span>Players: 20M+</span>
                <span>Genre: RPG</span>
              </div>
              <a href="#" class="btn">Play Now</a>
            </div>
          </div>
        </div>
        <div class="carousel-nav">
          <button class="evt-carousel-prev" aria-label="Evento anterior"><i class="fas fa-chevron-left"></i></button>
          <button class="evt-carousel-next" aria-label="Evento siguiente"><i
              class="fas fa-chevron-right"></i></button>
        </div>
      </section>






      <section id="game-events">


        <h2 class="neon-text text-center mb-4">Upcoming Game Events</h2>
        <div class="events-timeline">
          <!--Evento 1-->
          <div class="event-card-wrapper">
            <div class="event-card">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($eventos[0]['nombre']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($eventos[0]['fecha']) ?> •
                  <?= htmlspecialchars($eventos[0]['hora']) ?> GMT
                </p>
                <div class="event-details">
                  <span>Participants: <?= htmlspecialchars($eventos[0]['total_participantes']) ?> </span>
                  <span>• Game: <?= htmlspecialchars($eventos[0]['juego']) ?></span>
                </div>
                <a href="event.php?id=<?= htmlspecialchars($eventos[0]['id_evento']) ?>" class="btn btn-secondary">Read
                  more</a>
              </div>
            </div>
            <div class="event-banner"
              style="background-image: url('../events/images/<?= htmlspecialchars($eventos[0]['id_evento']) ?>.jpg');">
            </div>
          </div>
          <!--Evento 2-->
          <div class="event-card-wrapper">
            <div class="event-card">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($eventos[1]['nombre']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($eventos[1]['fecha']) ?> •
                  <?= htmlspecialchars($eventos[1]['hora']) ?> GMT
                </p>
                <div class="event-details">
                  <span>Participants: <?= htmlspecialchars($eventos[1]['total_participantes']) ?> </span>
                  <span>• Game: <?= htmlspecialchars($eventos[1]['juego']) ?></span>
                </div>
                <a href="event.php?id=<?= htmlspecialchars($eventos[1]['id_evento']) ?>" class="btn btn-secondary">Read
                  more</a>
              </div>
            </div>
            <div class="event-banner"
              style="background-image: url('../events/images/<?= htmlspecialchars($eventos[1]['id_evento']) ?>.jpg');">
            </div>
          </div>
          <!--Evento 3-->
          <div class="event-card-wrapper">
            <div class="event-card">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($eventos[2]['nombre']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($eventos[2]['fecha']) ?> •
                  <?= htmlspecialchars($eventos[2]['hora']) ?> GMT
                </p>
                <div class="event-details">
                  <span>Participants: <?= htmlspecialchars($eventos[2]['total_participantes']) ?> </span>
                  <span>• Game: <?= htmlspecialchars($eventos[2]['juego']) ?></span>
                </div>
                <a href="event.php?id=<?= htmlspecialchars($eventos[2]['id_evento']) ?>" class="btn btn-secondary">Read
                  more</a>
              </div>
            </div>
            <div class="event-banner"
              style="background-image: url('../events/images/<?= htmlspecialchars($eventos[2]['id_evento']) ?>.jpg');">
            </div>
          </div>
          <!--Evento 4-->
          <div class="event-card-wrapper">
            <div class="event-card">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($eventos[3]['nombre']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($eventos[3]['fecha']) ?> •
                  <?= htmlspecialchars($eventos[3]['hora']) ?> GMT
                </p>
                <div class="event-details">
                  <span>Participants: <?= htmlspecialchars($eventos[3]['total_participantes']) ?> </span>
                  <span>• Game: <?= htmlspecialchars($eventos[3]['juego']) ?></span>
                </div>
                <a href="event.php?id=<?= htmlspecialchars($eventos[3]['id_evento']) ?>" class="btn btn-secondary">Read
                  more</a>
              </div>
            </div>
            <div class="event-banner"
              style="background-image: url('../events/images/<?= htmlspecialchars($eventos[3]['id_evento']) ?>.jpg');">
            </div>
          </div>
          <!-- View all Events Button-->
          <div class="events-link">
            <a href="../HTML/events.php" class="btn">View All Events</a>
          </div>
      </section>

      <button class="evt-back-to-top" aria-label="Volver arriba"><i class="fas fa-arrow-up"></i></button>
    </div>
  </main>
  <footer>
    <div id="footer"></div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const carousel = document.querySelector('#gameCarousel .carousel-inner');
      const items = carousel.querySelectorAll('.carousel-item');
      const prevBtn = document.querySelector('.evt-carousel-prev');
      const nextBtn = document.querySelector('.evt-carousel-next');

      let currentIndex = 0;
      let autoplayInterval = null;
      const autoplayDelay = 5000; // 5 segundos

      function updateCarousel(index) {
        items.forEach((item, i) => {
          item.classList.remove('active');
          if (i === index) {
            item.classList.add('active');
          }
        });
      }

      function showNextSlide() {
        currentIndex = (currentIndex + 1) % items.length;
        updateCarousel(currentIndex);
      }

      function startAutoplay() {
        autoplayInterval = setInterval(showNextSlide, autoplayDelay);
      }

      function stopAutoplay() {
        clearInterval(autoplayInterval);
      }

      // Botones personalizados
      prevBtn.addEventListener('click', () => {
        stopAutoplay();
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        updateCarousel(currentIndex);
        startAutoplay();
      });

      nextBtn.addEventListener('click', () => {
        stopAutoplay();
        currentIndex = (currentIndex + 1) % items.length;
        updateCarousel(currentIndex);
        startAutoplay();
      });

      // Iniciar autoplay al cargar
      updateCarousel(currentIndex);
      startAutoplay();
    });

    const backToTop = $('.evt-back-to-top');
    $(window).scroll(function() {
      if ($(this).scrollTop() > 300) {
        backToTop.addClass('visible');
      } else {
        backToTop.removeClass('visible');
      }
    });

    backToTop.click(function() {
      $('html, body').animate({
        scrollTop: 0
      }, 500);
    });
  </script>


</body>

</html>