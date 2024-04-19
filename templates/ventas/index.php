<?= $this->fetch("./partials/header.php") ?>
<?= $this->fetch("./ventas/partials/nav.php") ?>

<main class="container">
  <!-- Graficas -->
  <?= $this->fetch("./ventas/components/facturacion-general.php") ?>
  <hr>
  <?= $this->fetch("./ventas/components/resumen-facturado.php") ?>
  <hr>
  <?= $this->fetch("./ventas/components/resumen-x-entidad.php") ?>
  <hr>
  <?= $this->fetch("./ventas/components/top-facturadores.php") ?>
</main>
<?= $this->fetch("./partials/loader.php") ?>
