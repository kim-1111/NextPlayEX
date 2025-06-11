<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NextPlay - Event Manager</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../CSS/principal.css">
  <link rel="stylesheet" href="../CSS/management.css">
  <link rel="stylesheet" href="../Layout/layout.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../Layout/include.js"></script>
  <script src="../Layout/auth.js"></script>
  <link rel="stylesheet" href="../CSS/about.css">
  <style>
    .message-box {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      padding: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      z-index: 1000;
      max-width: 80%;
      display: none;
    }
    .message-content {
      margin-right: 20px;
    }
    .close-btn {
      position: absolute;
      top: 5px;
      right: 10px;
      cursor: pointer;
      font-size: 18px;
      font-weight: bold;
    }
  </style>
</head>

<body>

  <div id="navbar"></div>
  <div id="loginwindow"></div>
  <div id="registerwindow"></div>

  <?php
  if (isset($_GET['message']) && !empty($_GET['message'])) {
    $mensaje = htmlspecialchars($_GET['message']);
    echo '
    <div class="message-box" id="messageBox">
      <span class="close-btn" onclick="closeMessage()">×</span>
      <div class="message-content">' . $mensaje . '</div>
    </div>
    <script>
      // Mostrar el mensaje al cargar la página
      document.addEventListener("DOMContentLoaded", function() {
        const messageBox = document.getElementById("messageBox");
        if (messageBox) {
          messageBox.style.display = "block";
          
        }
      });
      
      // Función para cerrar el mensaje manualmente y redirigir a eventmanager.php
      function closeMessage() {
        const messageBox = document.getElementById("messageBox");
        if (messageBox) {
          messageBox.style.display = "none";
          window.location.href = "../HTML/eventmanager.php";
        }
      }
    </script>
    ';
  }

  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $evento = $_SESSION['evento'] ?? [
    'id_evento' => '',
    'nombre' => '',
    'fecha' => '',
    'hora' => '',
    'descripcion' => '',
    'enlace_streaming' => '',
    'categoria' => '',
    'juego' => ''
  ];
  unset($_SESSION['evento']);
  ?>

  <div class="container">
    <div class="form-container">
      <h2>Gestión de Eventos</h2>
      <form action="../php/eventController.php" method="post" enctype="multipart/form-data">

        <input type="hidden" id="id_evento" name="id_evento" value="<?= htmlspecialchars($evento['id_evento']) ?>">

        <div class="form-group">
          <label for="nombre">Nombre (para buscar o eliminar):</label>
          <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($evento['nombre']) ?>">
        </div>
        <div class="form-group">
          <label for="fecha">Fecha:</label>
          <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($evento['fecha']) ?>">
        </div>
        <div class="form-group">
          <label for="hora">Hora:</label>
          <input type="time" id="hora" name="hora" value="<?= htmlspecialchars($evento['hora']) ?>">
        </div>
        <div class="form-group">
          <label for="descripcion">Descripción:</label>
          <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($evento['descripcion']) ?></textarea>
        </div>
        <div class="form-group">
          <label for="enlace_streaming">Enlace de Streaming (opcional):</label>
          <input type="url" id="enlace_streaming" name="enlace_streaming"
            value="<?= htmlspecialchars($evento['enlace_streaming']) ?>">
        </div>
        <div class="form-group">
          <label for="categoria">Categoría:</label>
          <select id="categoria_id" name="categoria_id">
            <option value="">Selecciona una categoría</option>
            <option value="1" <?= isset($evento['categoria_id']) && $evento['categoria_id'] == 1 ? 'selected' : '' ?>>
              Conferencia</option>
            <option value="2" <?= isset($evento['categoria_id']) && $evento['categoria_id'] == 2 ? 'selected' : '' ?>>
              Esports</option>
            <option value="3" <?= isset($evento['categoria_id']) && $evento['categoria_id'] == 3 ? 'selected' : '' ?>>Expo
            </option>
            <option value="4" <?= isset($evento['categoria_id']) && $evento['categoria_id'] == 4 ? 'selected' : '' ?>>
              Encuentro Social</option>
          </select>


        </div>

        <?php
        require_once '../php/eventController.php';

        $controller = new EventController();
        $conn = $controller->getConnection();
        try {
          $stmt = $conn->query("SELECT id_juego, nombre FROM juegos");
          $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          $juegos = [];
        }
        ?>



        <div class="form-group">
          <label for="juego">Juego relacionado:</label>
          <select id="juego_id" name="juego_id">
            <option value="">Selecciona un juego</option>
            <?php
            // Asumiendo que $juegos viene de la base de datos (id y nombre)
            foreach ($juegos as $juego) {
              $selected = (isset($evento['juego_id']) && $evento['juego_id'] == $juego['id_juego']) ? 'selected' : '';
              echo "<option value='{$juego['id_juego']}' $selected>{$juego['nombre']}</option>";
            }
            ?>
          </select>
        </div>

        <div class="form-group">
          <label for="imagen">Imagen del evento:</label>
          <input type="file" id="imagen" name="imagen" accept="image/*">
        </div>

        <button type="submit" name="create">¡Crear!</button>
        <button type="submit" name="search">¡Buscar!</button>
        <button type="submit" name="update">¡Actualizar!</button>
        <button type="submit" name="delete">¡Eliminar!</button>
      </form>

      <?php
      require_once '../php/eventController.php';

      $controller = new EventController();
      $conn = $controller->getConnection();
      $user_id = $_SESSION['user']['id_usuario'];

      try {
        $stmt = $conn->prepare("SELECT nombre, fecha, hora, descripcion, enlace_streaming, categoria_id_categoria, juegos_id_juego FROM eventos where promotores_id_promotor = :id_promotor");
        $stmt->execute([
          'id_promotor' => $user_id
        ]);
        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        die("Error al consultar eventos: " . $e->getMessage());
      }
      ?>

      <div class="listgames">
        <table>
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Descripción</th>
              <th>Streaming</th>
              <th>Categoría</th>
              <th>Juego</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($eventos && count($eventos) > 0) {
              foreach ($eventos as $evento) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($evento['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($evento['fecha']) . "</td>";
                echo "<td>" . htmlspecialchars($evento['hora']) . "</td>";
                echo "<td>" . htmlspecialchars($evento['descripcion']) . "</td>";
                echo "<td>" . htmlspecialchars($evento['enlace_streaming']) . "</td>";
                echo "<td>" . htmlspecialchars($evento['categoria_id_categoria']) . "</td>";
                echo "<td>" . htmlspecialchars($evento['juegos_id_juego']) . "</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='7'>No hay eventos registrados.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer id="footer">
  </footer>

</body>

</html>
