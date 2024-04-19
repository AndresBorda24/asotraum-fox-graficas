<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $__title ?></title>
  <?php if ($_ENV["APP_ENV"] == "prod"): ?>
    <?= $this->loadAssets($__asset, true) ?>
  <?php endif ?>
</head>
<body class="bg">
  <?= $content ?>

  <?php if ($_ENV["APP_ENV"] == "dev"): ?>
    <script type="module" src="http://localhost:5173/graficas/build/@vite/client"></script>
    <script type="module" src="http://localhost:5173/graficas/build/<?= $__asset ?>"></script>
  <?php endif ?>
</body>
</html>
