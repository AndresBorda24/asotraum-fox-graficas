<?= $this->fetch("./partials/header.php", [
  "title" => "Clínica Asotrauma | Estadísticas"
]) ?>
<main class="container py-4">
  <h1>Home Estadísticas</h1> 

  <div class="d-lg-grid gap-4" style="grid-template-columns: 1fr 1fr;">
    <?= $this->fetch("./home/partials/facturacion.php") ?>
    <?= $this->fetch("./home/partials/admisiones.php") ?>
  </div>
  <span x-data="iniciarGraficas"></span>
</main>