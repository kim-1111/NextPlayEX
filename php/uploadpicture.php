<?php

session_start();

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
  $targetDir = "../users/profileimg/";
  $fileName = $_SESSION['user']['nombre'] . ".jpg";
  $targetFilePath = $targetDir . $fileName;
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

  // Comprobar si es imagen
  $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
  if ($check === false) {
    echo "El archivo no es una imagen.";
    $uploadOk = 0;
  }

  // Limitar formatos permitidos
  $allowedTypes = ["jpg", "jpeg", "png", "gif"];
  if (!in_array($imageFileType, $allowedTypes)) {
    echo "Solo se permiten JPG, JPEG, PNG y GIF.";
    $uploadOk = 0;
  }

  // Subir archivo si todo está bien
  if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
      header("Location: ../HTML/perfil.php");
    } else {
      echo "Hubo un error al subir tu imagen.";
    }
  }
}
