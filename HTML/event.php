<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>NextPlay</title>

  <!-- Ícono de la página -->
  <link rel="icon" href="../imagenes/logo.png" />

  <!-- Archivos CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

  <!-- Fuentes personalizadas -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Press+Start+2P&display=swap"
    rel="stylesheet" />

  <!-- LAYOUT -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../Layout/include.js"></script>
  <script src="../Layout/auth.js"></script>
  <link rel="stylesheet" href="../Layout/layout.css">
  <link rel="stylesheet" href="../CSS/style.css" />
  <link rel="stylesheet" href="../CSS/event.css" />
  
  <link rel="stylesheet" href="../CSS/auth.css">
</head>

<body>
  <!-- Overlay y ventana de login -->
  <header>
    <div id="navbar"></div>
    <div id="loginwindow"></div>
    <div id="registerwindow"></div>
  </header>
<main>
  <div id="main-auth" class="container">
    <!-- Cabecera con navbar -->
    


    <?php
    require_once '../php/eventController.php';

    $evento = [
      'nombre' => 'EVENT NOT FOUND',
      'fecha' => 'never lol',
      'hora' => '',
      'descripcion' => '',
      'juego' => '',
      'categoria' => '',
      'total_participantes' => 0,
      'enlace_streaming' => '',
      'id_evento' => 0
    ];

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
      $controller = new EventController();
      $eventoEncontrado = $controller->getEventDetailsById((int) $_GET['id']);

      if ($eventoEncontrado) {
        $evento = $eventoEncontrado;
      }
    }
    ?>





    <body>


      <?php
      if (isset($_GET['message']) && !empty($_GET['message'])) {
        $mensaje = htmlspecialchars($_GET['message']);
        echo '
    <center>
        <div class="message-box" id="messageBox">
            ' . $mensaje . '
        </div>
    </center>
    </br></br></br></br>
    ';
      }
      ?>


      <div class="event-container">

        <img src="../events/images/<?= htmlspecialchars($evento['id_evento']) ?>.jpg" alt="Event Banner"
          class="event-banner">



        <h1 class="event-title"><?= htmlspecialchars($evento['nombre']) ?></h1>
        <div class="event-detail"><strong>Date: </strong><?= htmlspecialchars($evento['fecha']) ?></div>
        <div class="event-detail"><strong>Time: </strong><?= htmlspecialchars($evento['hora']) ?></div>
        <div class="event-detail"><strong>Description: </strong><?= htmlspecialchars($evento['descripcion']) ?></div>
        <div class="event-detail"><strong>Game: </strong><?= htmlspecialchars($evento['juego']) ?></div>
        <div class="event-detail"><strong>Category: </strong><?= htmlspecialchars($evento['categoria']) ?></div>
        <div class="event-detail"><strong>Participants
            People: </strong> <?= htmlspecialchars($evento['total_participantes']) ?></div>
        <a href="<?= htmlspecialchars($evento['enlace_streaming']) ?>" class="streaming-link">Watch Live Stream</a>

        <?php
        require_once '../php/eventController.php';
        $controller = new EventController();
        ?>

        <?php if (!empty($evento['id_evento'])): ?>
          <?php if (!isset($_SESSION['user']['id_usuario'])): ?>
            <p class="mt-3 text-danger">Login to sign on this event.</p>

          <?php else: ?>
            <?php if ($controller->checkifsignedon($evento['id_evento'])): ?>
              <!-- Botón para desapuntarse -->
              <form method="post" action="../php/eventController.php" class="mt-2">
                <input type="hidden" name="action" value="unsignonevent">
                <input type="hidden" name="id_evento" value="<?= htmlspecialchars($evento['id_evento']) ?>">
                <button type="submit" name="unsignon" class="btn btn-danger">Unsign</button>
              </form>

            <?php else: ?>
              <!-- Botón para apuntarse -->
              <form method="post" action="../php/eventController.php">
                <input type="hidden" name="action" value="signonevent">
                <input type="hidden" name="id_evento" value="<?= htmlspecialchars($evento['id_evento']) ?>">
                <button type="submit" name="signon" class="btn btn-primary mt-3">Sign on!</button>
              </form>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>


      </div></main>
    </body>

    <!-- Pie de página -->
    <!-- Footer -->
    <footer id="footer">
    </footer>
  </div>
</body>