<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $game = new GameController();

  if (isset($_POST["create"])) {
    $game->create();
  }

  if (isset($_POST["search"])) {
    $game->read();
  }

  if (isset($_POST["update"])) {
    $game->update();
  }

  if (isset($_POST["delete"])) {
    $game->delete();
  }

}


class GameController
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
      !isset($_POST['name']) ||
      !isset($_POST['developer']) ||
      !isset($_POST['publisher']) ||
      !isset($_POST['releasedate']) ||
      !isset($_POST['description']) ||
      !isset($_POST['link'])
    ) {
      header("Location: ../HTML/gamemanager.php?message=Faltan%20campos%20por%20llenar");
      exit();
    }



    $name = $_POST['name'];
    $developer = $_POST['developer'];
    $publisher = $_POST['publisher'];
    $releasedate = $_POST['releasedate'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    try {
      $stmt = $this->conn->prepare("INSERT INTO juegos (nombre, desarollador, editor, fecha_lanzamiento,descripcion,link) VALUES (:name, :developer, :publisher, :releasedate, :description, :link)");
      $stmt->execute([
        'name' => $name,
        'developer' => $developer,
        'publisher' => $publisher,
        'releasedate' => $releasedate,
        'description' => $description,
        'link' => $link
      ]);
      header("Location: ../HTML/gamemanager.php?message=Juego%20Creado!");
      exit();
    } catch (PDOException $e) {
      header("Location: ../HTML/gamemanager.php?message=Error%20al%20crear%20el%20juego");
      exit();
    }


  }

  public function update()
  {

    if (
      !isset($_POST['name']) ||
      !isset($_POST['developer']) ||
      !isset($_POST['publisher']) ||
      !isset($_POST['releasedate']) ||
      !isset($_POST['description']) ||
      !isset($_POST['link'])

    ) {
      header("Location: ../HTML/gamemanager.php?message=Faltan%20campos%20por%20llenar");
      exit();
    }

    // Recoger datos del formulario
    $name = $_POST['name'];
    $developer = $_POST['developer'];
    $publisher = $_POST['publisher'];
    $releaseDate = $_POST['releasedate'];
    $description = $_POST['description'];
    $link = $_POST['link'];

    try {
      // Actualizar el juego
      $stmt = $this->conn->prepare("
            UPDATE juegos 
            SET desarollador = :developer, editor = :publisher, fecha_lanzamiento = :releaseDate, descripcion = :description, link = :link 
            WHERE nombre = :name
        ");
      $stmt->execute([
        'developer' => $developer,
        'publisher' => $publisher,
        'releaseDate' => $releaseDate,
        'name' => $name,
        'description' => $description,
        'link' => $link
      ]);

      if ($stmt->rowCount() > 0) {
        header("Location: ../HTML/gamemanager.php?message=Juego%20actualizado%20correctamente");
        exit();
      } else {
        header("Location: ../HTML/gamemanager.php?message=No%20se%20encontr%C3%B3%20el%20juego%20para%20actualizar");
        exit();
      }

    } catch (PDOException $e) {
      header("Location: ../HTML/gamemanager.php?message=Error%20al%20actualizar%20el%20juego");
      exit();
    }

  }

  public function delete()
  {



    if (!isset($_POST['name'])) {
      header("Location: ../HTML/gamemanager.php?message=Falta%20el%20nombre%20del%20juego");
      exit();
    }


    $name = $_POST['name'];

    try {
      $stmt = $this->conn->prepare("DELETE FROM juegos WHERE nombre = :name");
      $stmt->execute(['name' => $name]);

      if ($stmt->rowCount() > 0) {
        header("Location: ../HTML/gamemanager.php?message=Se%20elimin%C3%B3%20correctamente");
        exit();
      } else {
        header("Location: ../HTML/gamemanager.php?message=Juego%20no%20encontrado%20o%20no%20eliminado");
        exit();
      }

    } catch (PDOException $e) {
      header("Location: ../HTML/gamemanager.php?message=Error%20al%20eliminar%20el%20juego");
      exit();
    }


  }

  public function read()
  {


    if (!isset($_POST['name'])) {
      header("Location: ../HTML/gamemanager.php?message=Falta%20el%20nombre%20del%20juego");
      exit();
    }


    $name = $_POST['name'];

    try {
      $stmt = $this->conn->prepare("SELECT nombre, desarollador, editor, fecha_lanzamiento,descripcion,link FROM juegos WHERE nombre = :name");
      $stmt->execute(['name' => $name]);
      $game = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($game) {
        $_SESSION['game'] = [
          "name" => $game['nombre'],
          "desarollador" => $game['desarollador'],
          "editor" => $game['editor'],
          'fecha_lanzamiento' => $game['fecha_lanzamiento'],
          "descripcion" => $game['descripcion'],
          "link" => $game['link']
        ];
        header("Location: ../HTML/gamemanager.php");
        exit();
      } else {

        header("Location: ../HTML/gamemanager.php?message=Juego%20no%20encontrado");
        exit();
      }
    } catch (PDOException $e) {
      header("Location: ../HTML/gamemanager.php?message=Error%20al%20buscar%20el%20juego");
    }


  }


  public function getbestgame()
  {
  try {
    $stmt = $this->conn->prepare("
      SELECT 
          j.id_juego,
          j.nombre,
          j.link,
          j.descripcion,
          j.image,
          COUNT(p.usuarios_id_usuario) AS total_participantes
      FROM juegos j
      JOIN eventos e ON j.id_juego = e.juegos_id_juego
      JOIN participa p ON e.id_evento = p.eventos_id_participa
      GROUP BY j.id_juego
      ORDER BY total_participantes DESC
      LIMIT 1
    ");

    $stmt->execute();
    $bestGame = $stmt->fetch(PDO::FETCH_ASSOC);

    return $bestGame;

  } catch (PDOException $e) {
    // Puedes manejar el error o devolver null
    return null;
  }
  }

  public function getAllGameNames()
  {
    try {
      $stmt = $this->conn->prepare("SELECT nombre FROM juegos");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_COLUMN); // Devuelve solo los valores de la columna 'nombre'
    } catch (PDOException $e) {
      return []; // Retorna un array vacío si hay error
    }
  }

}