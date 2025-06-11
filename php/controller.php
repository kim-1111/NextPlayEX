<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $user = new UserControllerSQL();
  if (isset($_POST["login"])) {
    $user->login();
  }
  if (isset($_POST["logout"])) {
    $user->logout();
  }
  if (isset($_POST["register"])) {
    $user->register();
  }
}

class UserControllerSQL
{
  private $conn;
  function __construct()
  {

    $servername = "nextplay-nextplay.l.aivencloud.com:11948";
    $username = "avnadmin";
    $password = "";
    $dbname = "nextplay";

    // Create connection
    $this->conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }

  /*private $staticUser = [
        'username' => 'user',
        'password' => '1234'
    ];
 
     if ($username == $this->staticUser['username'] && $passwd == $this->staticUser['password']) {
            $_SESSION['user'] = $username;
            $_SESSION['logged'] = "Inicio de sesión exitoso!";
            echo $_SESSION['logged'];
            echo $_SESSION['user'];
        } else {
            $_SESSION['error'] = "Credenciales inválidas";
            echo $_SESSION['error'];
        }
    */

  public function login()
  {

    $username = $_POST['username'];
    $passwd = $_POST['password'];

    $stmt = $this->conn->prepare(query: "SELECT id_usuario, nombre, correo, contrasena, estadisticas, img FROM usuarios WHERE nombre = ? AND contrasena = ?");
    $stmt->bind_param("ss", $username, $passwd);
    $stmt->execute();
    $stmt->bind_result($id_usuario, $nombre, $email, $contrasena, $estadisticas, $img);

    //SI EL INICIO DE SESIÓN DE USUARIOS SE LOGRÓ:
    if ($stmt->fetch()) {
      $_SESSION['logged'] = true;
      $_SESSION['user'] = [
        "id_usuario" => $id_usuario,
        "nombre" => $nombre,
        "email" => $email,
        "contrasena" => $contrasena,
        'estadisticas' => $estadisticas,
        "img" => $img,
        "promotor" => false
      ];


      $this->conn->close();

      header(header: "Location: ../HTML/profile.php");
      exit();
    } else {
      $_SESSION['logged'] = false;
    }
    $stmt->close();


    //SI EL INICIO DE SESIÓN DE PROMOTORES SE LOGRÓ:
    $stmtP = $this->conn->prepare(query: "SELECT id_promotor, nombre, correo, contrasena, contacto FROM promotores WHERE nombre = ? AND contrasena = ?");
    $stmtP->bind_param("ss", $username, $passwd);
    $stmtP->execute();
    $stmtP->bind_result($id_promotor, $nombre, $email, $contrasena, $contacto);

    if ($stmtP->fetch()) {
      $_SESSION['logged'] = true;
      $_SESSION['user'] = [
        "id_usuario" => $id_promotor,
        "nombre" => $nombre,
        "email" => $email,
        "contrasena" => $passwd,
        "contacto" => $contacto,
        "promotor" => true
      ];

      $this->conn->close();

      header(header: "Location: ../HTML/profilepromotor.php");
      exit();
    } else {
      $_SESSION['logged'] = false;
    }
    $stmtP->close();

    //SI HUBO ALGÚN ERROR:
    if ($_SESSION['logged'] == false) {
      header("Location: ../HTML/err.html");
      exit();
    }
  }

  public function logout()
  {
    if (isset($_POST["logout"])) {
      session_unset();
      session_destroy();
      header("Location: ../HTML/logout.html");
      exit();
    }
  }


  public function register()
  {
    // Retrieve form data from POST request
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Verifica que las contraseñas coincidan
    if ($password !== $repeat_password) {
      $_SESSION['error'] = "Las contraseñas no coinciden";
      header("Location: ../HTML/register.php");
      exit();
    }

    //VERIFICAR LA CONTRASEÑÄ
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
      $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres, una mayúscula y un número.";
      header("Location: ../HTML/register.php");
      exit();
    }

    //VERIFICAR EL CORREO
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = "El formato del correo electrónico no es válido.";
      header("Location: ../HTML/register.php");
      exit();
    }

    $rol = $_POST['rol'];
    // Datos sanitizados y hash de la contraseña
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);

    // Determinar tabla de destino
    if ($rol === "usuario") {
      $tabla = "usuarios";
    } elseif ($rol === "promotor") {
      $tabla = "promotores";
    } else {
      $_SESSION['error'] = "Tipo de usuario no especificado";
      header("Location: ../HTML/register.php");
      exit();
    }

    // Inserción en la base de datos
    $stmt = $this->conn->prepare("INSERT INTO $tabla (nombre, correo, contrasena) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
      // Obtener el ID del nuevo usuario
      $id_usuario = $this->conn->insert_id;

      // Establecer datos en la sesión
      $_SESSION['logged'] = true;
      $_SESSION['user'] = [
        "id_usuario" => $id_usuario,
        "nombre" => $username,
        "email" => $email,
        "contrasena" => $password,
        'estadisticas' => [],
        "img" => null,
        "promotor" => false
      ];

      unset($_SESSION['error']);
      $stmt->close();
      header("Location: ../HTML/profile.php");
      exit();
    } else {
      $_SESSION['error'] = "Error al registrar el usuario.";
      $stmt->close();
      header("Location: ../HTML/register.php");
      exit();
    }
  }



}