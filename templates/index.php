<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index</title>
  <link
  rel="stylesheet"
  integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
  crossorigin="anonymous">
  <?= $this->loadAssets('index') ?>
</head>
<body class="bg-body-tertiary">
  <?= $this->fetch("./partials/header.php") ?>

  <!-- Graficas -->
  <?= $this->fetch("./factu/grafica-1.php") ?>
</body>
</html>
