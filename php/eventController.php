<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $event = new EventController();

  if (isset($_POST["create"])) {
    $event->create();
  }

  if (isset($_POST["search"])) {
    $event->read();
  }

  if (isset($_POST["update"])) {
    $event->update();
  }

  if (isset($_POST["delete"])) {
    $event->delete();
  }

  if (isset($_POST['signon'])) {

    $eventid = $_POST['id_evento'];
    $event->signon($eventid, $_SESSION['user']['id_usuario']);
  }

  if (isset($_POST['unsignon'])) {

    $eventid = $_POST['id_evento'];
    $event->unsignevent($eventid, $_SESSION['user']['id_usuario']);
  }



}

if (isset($_GET['action']) && $_GET['action'] === 'getEventsJSON') {
  $controller = new EventController();
  $controller->getEventsJSON();
}

class EventController
{

  private $conn;
  function __construct()
  {
    $servername = "nextplay-nextplay.l.aivencloud.com:11948";
    $username = "avnadmin";
    $password = "";
    $dbname = "nextplay";

    try {
      $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Connection failed: " . $e->getMessage());
    }
  }

  public function getConnection()
  {
    return $this->conn;
  }


  public function create()
  {
    if (
      !isset($_POST['nombre']) ||
      !isset($_POST['fecha']) ||
      !isset($_POST['hora']) ||
      !isset($_POST['descripcion']) ||
      !isset($_POST['categoria_id']) ||
      !isset($_POST['juego_id'])
    ) {
      header("Location: ../HTML/eventmanager.php?message=Faltan%20campos%20por%20llenar");
      exit();
    }

    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $descripcion = $_POST['descripcion'];
    $categoria_id = $_POST['categoria_id'];
    $juego_id = $_POST['juego_id'];
    $enlace_streaming = $_POST['enlace_streaming'] ?? null;
    $id_promotor = $_SESSION['user']['id_usuario'];

    try {
      $stmt = $this->conn->prepare("
      INSERT INTO eventos (nombre, fecha, hora, descripcion, enlace_streaming, categoria_id_categoria, juegos_id_juego, promotores_id_promotor)
      VALUES (:nombre, :fecha, :hora, :descripcion, :enlace_streaming, :categoria_id, :juego_id, :id_promotor)
    ");

      $stmt->execute([
        'nombre' => $nombre,
        'fecha' => $fecha,
        'hora' => $hora,
        'descripcion' => $descripcion,
        'enlace_streaming' => $enlace_streaming,
        'categoria_id' => $categoria_id,
        'juego_id' => $juego_id,
        'id_promotor' => $id_promotor
      ]);

      // Obtener el ID del evento recién insertado
      $eventoId = $this->conn->lastInsertId();

      // Guardar la imagen si se subió
      if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $tmpName = $_FILES['imagen']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

        if ($ext === 'jpg' || $ext === 'jpeg') {
          $destination = __DIR__ . "/../events/images/{$eventoId}.jpg";
          move_uploaded_file($tmpName, $destination);
        } else {
          // Elimina el evento creado si se subió un archivo inválido
          $this->conn->prepare("DELETE FROM eventos WHERE id_evento = :id")->execute(['id' => $eventoId]);
          header("Location: ../HTML/eventmanager.php?message=Solo%20se%20permiten%20imágenes%20JPG");
          exit();
        }
      }

      header("Location: ../HTML/eventmanager.php?message=Evento%20creado%20correctamente");
      exit();
    } catch (PDOException $e) {
      header("Location: ../HTML/eventmanager.php?message=Error%20al%20crear%20el%20evento");
      exit();
    }
  }

  public function update()
  {
    if (
      !isset($_POST['nombre']) ||
      !isset($_POST['fecha']) ||
      !isset($_POST['hora']) ||
      !isset($_POST['descripcion']) ||
      !isset($_POST['categoria_id']) ||
      !isset($_POST['juego_id'])
    ) {
      header("Location: ../HTML/eventmanager.php?message=Faltan%20campos%20por%20llenar");
      exit();
    }

    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $descripcion = $_POST['descripcion'];
    $categoria_id = $_POST['categoria_id'];
    $juego_id = $_POST['juego_id'];
    $enlace_streaming = $_POST['enlace_streaming'] ?? null;
    $id_promotor = $_SESSION['user']['id_usuario'];
    
    try {
      // Buscar el evento solo por nombre
      $findStmt = $this->conn->prepare("
        SELECT id_evento 
        FROM eventos 
        WHERE nombre = :nombre 
        AND promotores_id_promotor = :id_promotor
      ");
      
      $findStmt->execute([
        'nombre' => $nombre,
        'id_promotor' => $id_promotor
      ]);
      
      $evento = $findStmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$evento) {
        header("Location: ../HTML/eventmanager.php?message=No%20se%20encontró%20el%20evento%20con%20ese%20nombre");
        exit();
      }
      
      $id = $evento['id_evento'];
      
      // Actualizar el evento encontrado
      $stmt = $this->conn->prepare("
      UPDATE eventos 
      SET nombre = :nombre,
          fecha = :fecha,
          hora = :hora,
          descripcion = :descripcion,
          enlace_streaming = :enlace_streaming, 
          categoria_id_categoria = :categoria_id, 
          juegos_id_juego = :juego_id,
          promotores_id_promotor = :id_promotor
      WHERE id_evento = :id
      ");

      $stmt->execute([
        'nombre' => $nombre,
        'fecha' => $fecha,
        'hora' => $hora,
        'descripcion' => $descripcion,
        'enlace_streaming' => $enlace_streaming,
        'categoria_id' => $categoria_id,
        'juego_id' => $juego_id,
        'id_promotor' => $id_promotor,
        'id' => $id
      ]);

      // Subida de imagen
      if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['imagen']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

        if ($ext === 'jpg' || $ext === 'jpeg') {
          $destination = __DIR__ . "/../events/images/{$id}.jpg";
          move_uploaded_file($tmpName, $destination);
        } else {
          header("Location: ../HTML/eventmanager.php?message=Solo%20se%20permiten%20imágenes%20JPG");
          exit();
        }
      }

      header("Location: ../HTML/eventmanager.php?message=Evento%20actualizado%20correctamente");
      exit();

    } catch (PDOException $e) {
      header("Location: ../HTML/eventmanager.php?message=Error%20al%20actualizar%20el%20evento:%20" . urlencode($e->getMessage()));
      exit();
    }
  }




  public function read()
  {

    if (!isset($_POST['nombre'])) {
      header("Location: ../HTML/eventmanager.php?message=Falta%20el%20nombre%20del%20evento");
      exit();
    }

    $nombre = $_POST['nombre'];

    try {

      $stmt = $this->conn->prepare("
        SELECT e.id_evento, e.nombre, e.fecha, e.hora, e.descripcion, e.enlace_streaming, e.categoria_id_categoria, c.nombre AS categoria_nombre,
               e.juegos_id_juego, j.nombre AS juego_nombre
        FROM eventos e
        LEFT JOIN categoria c ON e.categoria_id_categoria = c.id_categoria
        LEFT JOIN juegos j ON e.juegos_id_juego = j.id_juego
        WHERE e.nombre = :nombre
      ");
      $stmt->execute(['nombre' => $nombre]);
      $event = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($event) {
        $_SESSION['evento'] = [
          "id_evento" => $event['id_evento'],
          "nombre" => $event['nombre'],
          "fecha" => $event['fecha'],
          "hora" => $event['hora'],
          "descripcion" => $event['descripcion'],
          "enlace_streaming" => $event['enlace_streaming'],
          "categoria_id" => $event['categoria_id_categoria'],
          "categoria_nombre" => $event['categoria_nombre'],
          "juego_id" => $event['juegos_id_juego'],
          "juego_nombre" => $event['juego_nombre']
        ];
        header("Location: ../HTML/eventmanager.php");
        exit();
      } else {
        header("Location: ../HTML/eventmanager.php?message=Evento%20no%20encontrado");
        exit();
      }
    } catch (PDOException $e) {
      header("Location: ../HTML/eventmanager.php?message=Error%20al%20buscar%20el%20evento");
      exit();
    }
  }


  public function delete()
  {
    if (!isset($_POST['nombre'])) {
      header("Location: ../HTML/eventmanager.php?message=Falta%20el%20nombre%20del%20evento");
      exit();
    }

    $nombre = $_POST['nombre'];

    try {
      // Buscar el ID del evento antes de eliminarlo
      $stmt = $this->conn->prepare("SELECT id_evento FROM eventos WHERE nombre = :nombre");
      $stmt->execute(['nombre' => $nombre]);
      $evento = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$evento) {
        header("Location: ../HTML/eventmanager.php?message=Evento%20no%20encontrado");
        exit();
      }

      $id = $evento['id_evento'];

      // Eliminar el evento
      $deleteStmt = $this->conn->prepare("DELETE FROM eventos WHERE id_evento = :id");
      $deleteStmt->execute(['id' => $id]);

      if ($deleteStmt->rowCount() > 0) {
        // Eliminar imagen asociada si existe
        $imagePath = __DIR__ . "/../Events/images/{$id}.jpg";
        if (file_exists($imagePath)) {
          unlink($imagePath);
        }

        header("Location: ../HTML/eventmanager.php?message=Evento%20eliminado%20correctamente");
        exit();
      } else {
        header("Location: ../HTML/eventmanager.php?message=Evento%20no%20eliminado");
        exit();
      }
    } catch (PDOException $e) {
      header("Location: ../HTML/eventmanager.php?message=Error%20al%20eliminar%20el%20evento");
      exit();
    }
  }

  public function returnrecenteventsprincipal()
  {
    try {
      $stmt = $this->conn->prepare("
            SELECT
                e.id_evento,
                e.nombre,
                e.fecha,
                e.hora,
                j.nombre AS juego,
                COUNT(p.usuarios_id_usuario) AS total_participantes
            FROM eventos e
            LEFT JOIN juegos j ON e.juegos_id_juego = j.id_juego
            LEFT JOIN participa p ON e.id_evento = p.eventos_id_participa
            WHERE e.fecha >= CURDATE()
            GROUP BY e.id_evento, e.nombre, e.fecha, e.hora, j.nombre
            ORDER BY e.fecha ASC, e.hora ASC
            LIMIT 4
        ");
      $stmt->execute();
      $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $eventos;
    } catch (PDOException $e) {
      return [];
    }
  }

  public function getEventDetailsById($id)
  {
    try {
      $stmt = $this->conn->prepare("
      SELECT
        e.id_evento,
        e.nombre,
        e.fecha,
        e.hora,
        e.descripcion,
        e.enlace_streaming,
        j.nombre AS juego,
        c.nombre AS categoria,
        COUNT(p.usuarios_id_usuario) AS total_participantes
      FROM eventos e
      LEFT JOIN juegos j ON e.juegos_id_juego = j.id_juego
      LEFT JOIN categoria c ON e.categoria_id_categoria = c.id_categoria
      LEFT JOIN participa p ON e.id_evento = p.eventos_id_participa
      WHERE e.id_evento = :id
      GROUP BY 
        e.id_evento, e.nombre, e.fecha, e.hora, e.descripcion, e.enlace_streaming,
        j.nombre, c.nombre
    ");

      $stmt->execute(['id' => $id]);
      $evento = $stmt->fetch(PDO::FETCH_ASSOC);

      return $evento ?: null;

    } catch (PDOException $e) {
      return null;
    }
  }

  public function signon($eventid, $userid)
  {

    try {
      $stmt = $this->conn->prepare("
      INSERT INTO participa (eventos_id_participa, usuarios_id_usuario)
      VALUES (:evento_id, :usuario_id)
    ");

      $stmt->execute([
        'evento_id' => $eventid,
        'usuario_id' => $userid
      ]);
      header("Location: ../HTML/event.php?id=$eventid&message=You%20are%20now%20part%20of%20this%20event!");

    } catch (PDOException $e) {
      header("Location: ../HTML/event.php?id=$eventid&message=You%20are%20already%20in!");
    }

  }

  public function checkifsignedon($eventid)
  {

    if (!isset($_SESSION['user']['id_usuario'])) {
      return false;
    }

    $userId = $_SESSION['user']['id_usuario'];

    try {
      $stmt = $this->conn->prepare("
      SELECT 1 FROM participa 
      WHERE eventos_id_participa = :evento_id 
      AND usuarios_id_usuario = :usuario_id
    ");

      $stmt->execute([
        'evento_id' => $eventid,
        'usuario_id' => $userId
      ]);

      return $stmt->fetch() ? true : false;

    } catch (PDOException $e) {
      return false;
    }
  }

  public function unsignevent($eventid, $userid)
  {
    try {
      $stmt = $this->conn->prepare("
      DELETE FROM participa WHERE eventos_id_participa = :evento_id AND usuarios_id_usuario = :usuario_id
    ");

      $stmt->execute([
        'evento_id' => $eventid,
        'usuario_id' => $userid
      ]);
      header("Location: ../HTML/event.php?id=$eventid&message=You%20unsigned%20from%20this%20event!");

    } catch (PDOException $e) {
      header("Location: ../HTML/event.php?id=$eventid&message=Error%20while%20unsigning!");
    }
  }


  public function getEventsJSON()
  {
    try {
      $stmt = $this->conn->query("SELECT nombre, fecha, enlace_streaming FROM eventos");
      $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $result = [];
      foreach ($eventos as $evento) {
        $result[] = [
          'date' => $evento['fecha'],
          'title' => $evento['nombre'],
          'type' => $evento['enlace_streaming'] ? 'live' : 'soon'
        ];
      }
      header('Content-Type: application/json');
      echo json_encode($result);
      exit();

    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(['error' => 'Error fetching events']);
      exit();
    }
  }

  public function countAllEvents()
  {
    try {
      $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM eventos");
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['total'] ?? 0;
    } catch (PDOException $e) {
      return 0;
    }
  }

  public function countUniqueParticipants()
  {
    try {
      $stmt = $this->conn->prepare("SELECT COUNT(DISTINCT usuarios_id_usuario) AS total FROM participa");
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'] ?? 0;
    } catch (PDOException $e) {
      return 0;
    }
  }


  public function getAllActiveEvents()
  {
    try {
      $stmt = $this->conn->prepare("
SELECT
    e.id_evento,
    e.nombre,
    e.fecha,
    e.hora,
    j.nombre AS juego,
    c.nombre AS categoria,
    COUNT(p.usuarios_id_usuario) AS total_participantes
FROM eventos e
LEFT JOIN juegos j ON e.juegos_id_juego = j.id_juego
LEFT JOIN categoria c ON e.categoria_id_categoria = c.id_categoria
LEFT JOIN participa p ON e.id_evento = p.eventos_id_participa
WHERE e.fecha >= CURDATE()
GROUP BY
    e.id_evento,
    e.nombre,
    e.fecha,
    e.hora,
    j.nombre,
    c.nombre
ORDER BY e.fecha ASC, e.hora ASC;
        ");
      $stmt->execute();
      $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $eventos;
    } catch (PDOException $e) {
      return [];
    }
  }

  public function getAllExpiredEvents()
  {
    try {
      $stmt = $this->conn->prepare("
SELECT
    e.id_evento,
    e.nombre,
    e.fecha,
    e.hora,
    j.nombre AS juego,
    c.nombre AS categoria,
    COUNT(p.usuarios_id_usuario) AS total_participantes
FROM eventos e
LEFT JOIN juegos j ON e.juegos_id_juego = j.id_juego
LEFT JOIN categoria c ON e.categoria_id_categoria = c.id_categoria
LEFT JOIN participa p ON e.id_evento = p.eventos_id_participa
WHERE e.fecha < CURDATE()
GROUP BY
    e.id_evento,
    e.nombre,
    e.fecha,
    e.hora,
    j.nombre,
    c.nombre
ORDER BY e.fecha DESC, e.hora DESC;
        ");
      $stmt->execute();
      $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $eventos;
    } catch (PDOException $e) {
      return [];
    }
  }


  public function getAllCategoryNames()
  {
    try {
      $stmt = $this->conn->prepare("SELECT nombre FROM categoria");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
      return []; // En caso de error, retorna un array vacío
    }
  }
}
?>
