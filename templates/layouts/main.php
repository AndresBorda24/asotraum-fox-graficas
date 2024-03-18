<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $__title ?></title>
  <link
  rel="stylesheet"
  integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
  crossorigin="anonymous">
  
  <?php if ($_ENV["APP_ENV"] == "prod"): ?>
    <?= $this->loadAssets($__asset, true) ?>
  <?php endif ?>
</head>
<body class="bg-body-tertiary">
  <?= $content ?>

  <?php if ($_ENV["APP_ENV"] == "dev"): ?>
    <script type="module" src="http://localhost:5173/graficas/build/@vite/client"></script>
    <script type="module" src="http://localhost:5173/graficas/build/<?= $__asset ?>"></script>
  <?php else: ?>
    <?= $this->loadAssets($__asset) ?>
  <?php endif ?>
</body>
</html>
