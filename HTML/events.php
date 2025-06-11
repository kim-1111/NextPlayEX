<?php
require_once '../php/EventController.php';
require_once '../php/gameController.php';


$controller = new EventController();
$totalEventos = $controller->countAllEvents();
$totalParticipantes = $controller->countUniqueParticipants();
$currentDate = date('Y-m-d');


$gameController = new GameController();
$gameNames = $gameController->getAllGameNames();
$categoryNames = $controller->getAllCategoryNames();


$eventController = new EventController();
$activeEvents = $eventController->getAllActiveEvents();
$expiredEvents = $eventController->getAllExpiredEvents();


$eventoscarr = $controller->returnrecenteventsprincipal();
?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Central - NextPlay</title>
  <link rel="icon" href="../imagenes/logo.png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../Layout/layout.css">
  <link rel="stylesheet" href="../CSS/events.css">
  <link rel="stylesheet" href="../CSS/principal.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../Layout/include.js"></script>
  <script src="../Layout/auth.js"></script>
  <script src="../Layout/events.js"></script>
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
      <section class="evt-carousel" aria-label="Eventos destacados">
        <div class="evt-carousel-wrapper">
          <div class="evt-info-panel">
            <h2 class="evt-neon-title">EVENT CENTRAL</h2>
            <p class="evt-info-text">Discover the hottest events in the gaming world! Compete, connect, and conquer in exclusive tournaments!</p>
            <div class="evt-stats">
              <div class="evt-stat-item">
                <div class="evt-stat-number"> <?= $totalEventos ?></div>
                <div class="evt-stat-label">Active Events</div>
              </div>
              <div class="evt-stat-item">
                <div class="evt-stat-number"><?= $totalParticipantes ?></div>
                <div class="evt-stat-label">Participants</div>
              </div>
            </div>
          </div>
          <div class="evt-carousel-slides">
            <div class="evt-carousel-slide active" role="group">
              <a href="event.php?id=<?= htmlspecialchars($eventoscarr[0]['id_evento']) ?>"><img
                  src="../events/images/<?= htmlspecialchars($eventoscarr[0]['id_evento']) ?>.jpg"
                  alt="Evento Fortnite"></a>
            </div>
            <div class="evt-carousel-slide" role="group">
              <a href="event.php?id=<?= htmlspecialchars($eventoscarr[1]['id_evento']) ?>"><img
                  src="../events/images/<?= htmlspecialchars($eventoscarr[1]['id_evento']) ?>.jpg"
                  alt="Evento Fortnite"></a>
            </div>
            <div class="evt-carousel-slide" role="group">
              <a href="event.php?id=<?= htmlspecialchars($eventoscarr[2]['id_evento']) ?>"><img
                  src="../events/images/<?= htmlspecialchars($eventoscarr[2]['id_evento']) ?>.jpg"
                  alt="Evento Fortnite"></a>
            </div>
            <button class="evt-carousel-prev" aria-label="Evento anterior"><i class="fas fa-chevron-left"></i></button>
            <button class="evt-carousel-next" aria-label="Evento siguiente"><i
                class="fas fa-chevron-right"></i></button>
            <div class="evt-carousel-indicators">
              <button class="active" aria-label="Slide 1"></button>
              <button aria-label="Slide 2"></button>
              <button aria-label="Slide 3"></button>
            </div>
          </div>
        </div>
      </section>

      <section class="evt-calendar-container" aria-label="Calendario de eventos">
        <div class="evt-calendar-header">
          <div class="evt-time-indicator">
            <span class="evt-glowing-dot"></span>
            <span class="evt-current-date"><?= $currentDate ?></span>
          </div>
          <div class="month-nav">
            <button class="evt-neon-btn prev-year" aria-label="Año anterior"><i
                class="fas fa-angle-double-left"></i></button>
            <button class="evt-neon-btn prev-month" aria-label="Mes anterior"><i
                class="fas fa-chevron-left"></i></button>
            <span class="month-year-display">Mayo 2025</span>
            <button class="evt-neon-btn next-month" aria-label="Mes siguiente"><i
                class="fas fa-chevron-right"></i></button>
            <button class="evt-neon-btn next-year" aria-label="Año siguiente"><i
                class="fas fa-angle-double-right"></i></button>
          </div>
        </div>
        <div class="month-scroll">
          <button class="month-tab" data-month="0">JAN</button>
          <button class="month-tab" data-month="1">FEB</button>
          <button class="month-tab" data-month="2">MAR</button>
          <button class="month-tab" data-month="3">APR</button>
          <button class="month-tab" data-month="4">MAY</button>
          <button class="month-tab" data-month="5">JUN</button>
          <button class="month-tab" data-month="6">JUL</button>
          <button class="month-tab" data-month="7">AGO</button>
          <button class="month-tab" data-month="8">SEP</button>
          <button class="month-tab" data-month="9">OCT</button>
          <button class="month-tab" data-month="10">NOV</button>
          <button class="month-tab" data-month="11">DEC</button>
        </div>

        <script>
          // Obtiene el mes actual (0-11)
          const currentMonth = new Date().getMonth();

          // Selecciona todos los botones
          const monthButtons = document.querySelectorAll('.month-tab');

          // Quita la clase active-month de todos
          monthButtons.forEach(btn => btn.classList.remove('active-month'));

          // Agrega la clase active-month al botón correspondiente al mes actual
          const activeButton = document.querySelector(`.month-tab[data-month="${currentMonth}"]`);
          if (activeButton) {
            activeButton.classList.add('active-month');
          }
        </script>
        <div class="week-numbers">
          <span>MON</span>
          <span>TUE</span>
          <span>WED</span>
          <span>THU</span>
          <span>FRI</span>
          <span>SAT</span>
          <span>SUN</span>
        </div>
        <div class="date-grid" role="grid" aria-label="Días del mes"></div>
        <div class="calendar-status">
          <div class="status-items">
            <div class="status-item">
              <span class="status-led led-green"></span>
              <span>ACTIVE EVENTS: <?= $totalEventos ?></span>
            </div>
            <div class="status-item">
              <span class="status-led led-blue"></span>
              <span>TOTAL PARTICIPANTS: <?= $totalParticipantes ?></span>
            </div>
          </div>
          <div class="calendar-controls">
            <button class="evt-neon-btn" aria-label="Sincronizar calendario"><i class="fas fa-sync"></i> Sync
              Calendar</button>
            <button class="evt-neon-btn" aria-label="Vista de cuadrícula"><i class="fas fa-th"></i> Grid View</button>
          </div>
        </div>
      </section>

      <section class="evt-tabbed-section" aria-label="Galería de eventos">
        <div class="evt-filter-bar">
          <select class="evt-filter-select" id="game-filter" aria-label="Seleccionar juego">
            <option value="all">All Games</option>
            <?php foreach ($gameNames as $game): ?>
              <option value="<?= htmlspecialchars(strtolower($game)) ?>"><?= htmlspecialchars($game) ?></option>
            <?php endforeach; ?>
          </select>
          <select class="evt-filter-select" id="type-filter" aria-label="Seleccionar tipo">
            <option value="all">All Types</option>
            <?php foreach ($categoryNames as $category): ?>
              <option value="<?= htmlspecialchars(strtolower($category)) ?>"><?= htmlspecialchars($category) ?></option>
            <?php endforeach; ?>
          </select>
          <button class="evt-neon-btn" id="reset-filters" aria-label="Restablecer filtros">Reset Filters</button>
        </div>
        <div class="evt-nav-tabs" role="tablist">
          <button class="evt-nav-link active" data-tab="next-24h" role="tab" aria-selected="true">Events
            Activos</button>
          <button class="evt-nav-link" data-tab="past" role="tab" aria-selected="false">PAST EVENTS</button>
        </div>
        <div class="evt-tab-content">

          <!-- ACTIVE EVENTS -->
          <div class="evt-event-gallery" id="next-24h" role="tabpanel" aria-hidden="false">
            <?php if (!empty($activeEvents)): ?>
              <?php foreach ($activeEvents as $event): ?>
                <div class="evt-event-card">
                  <img class="evt-event-logo" src="../events/images/<?= strtolower($event['id_evento']) ?>.jpg"
                    alt="Evento <?= htmlspecialchars($event['nombre']) ?>">
                  <div class="evt-event-content">
                    <h5><?= htmlspecialchars($event['nombre']) ?></h5>
                    <div class="evt-event-info">
                      <i class="fas fa-clock"></i>
                      <span><?= $event['fecha'] . ' ' . substr($event['hora'], 0, 5) ?></span>
                    </div>
                    <div class="evt-event-details">
                      <?= $event['total_participantes'] ?> Participantes • </br>
                      Juego: <?= htmlspecialchars($event['juego']) ?> •</br>
                      Categoría: <?= htmlspecialchars($event['categoria']) ?> •</br>
                    </div>
                    <button class="evt-join-btn" onclick="window.location.href='event.php?id=<?= $event['id_evento'] ?>'"
                      aria-label="Unirse al <?= htmlspecialchars($event['nombre']) ?>">
                      UNIRSE
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="evt-no-events">There are no active events.</div>
            <?php endif; ?>
          </div>

          <!-- EXPIRED EVENTS -->
          <div class="evt-event-gallery" id="past" role="tabpanel" aria-hidden="true">
            <?php if (!empty($expiredEvents)): ?>
              <?php foreach ($expiredEvents as $event): ?>
                <div class="evt-event-card">
                  <img class="evt-event-logo" src="../events/images/<?= strtolower($event['id_evento']) ?>.jpg"
                    alt="Evento <?= htmlspecialchars($event['nombre']) ?>">
                  <div class="evt-event-content">
                    <h5><?= htmlspecialchars($event['nombre']) ?></h5>
                    <div class="evt-event-info">
                      <i class="fas fa-clock"></i>
                      <span><?= $event['fecha'] ?></span>
                    </div>
                    <div class="evt-event-details">
                      <?= $event['total_participantes'] ?> Participantes •</br>
                      Juego: <?= htmlspecialchars($event['juego']) ?> •</br>
                      Categoría: <?= htmlspecialchars($event['categoria']) ?> •</br>
                    </div>
                    <button class="evt-join-btn" disabled aria-label="Evento concluido">COMPLETED</button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="evt-no-events">There are no expired events.</div>
            <?php endif; ?>
          </div>
      </section>

      <button class="evt-back-to-top" aria-label="Volver arriba"><i class="fas fa-arrow-up"></i></button>
    </div>
  </main>
  <div id="footer"></div>
</body>

</html>