<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NextPlay - Search</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../CSS/principal.css">
  <link rel="stylesheet" href="../CSS/management.css">
  <link rel="stylesheet" href="../CSS/search.css">
  <link rel="stylesheet" href="../Layout/layout.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../Layout/include.js"></script>
  <script src="../Layout/auth.js"></script>
  <link rel="stylesheet" href="../CSS/about.css">
</head>

<body>

  <div id="navbar"></div>
  <div id="loginwindow"></div>
  <div id="registerwindow"></div>

<div class="main">

    <h1>Search Results</h1>
<?php
require_once '../php/eventController.php';

$searchInput = $_GET['search-input'] ?? '';

$controller = new EventController();
$results = $controller->searchEvents($searchInput);
?>

<div class="card-container">
    <?php if (!empty($results)): ?>
        <?php foreach ($results as $evento): ?>
            <div class="card">
                <div class="card-image">
                        <img src="../events/images/<?php echo htmlspecialchars($evento['id_evento']); ?>.jpg" alt="Imagen del evento">
                </div>
                <div class="card-content">
                    <h3><a href="event.php?id=<?php echo htmlspecialchars($evento['id_evento']); ?>"><?php echo htmlspecialchars($evento['nombre']); ?></a></h3>
                    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($evento['fecha']); ?></p>
                    <p><strong>Juego:</strong> <?php echo htmlspecialchars($evento['juego']); ?></p>
                    <p><strong>Categoría:</strong> <?php echo htmlspecialchars($evento['categoria']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No se encontraron resultados para "<?php echo htmlspecialchars($searchInput); ?>".</p>
    <?php endif; ?>
</div>

</div>

  <div id="footer"></div>

</body>

</html>