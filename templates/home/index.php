<?= $this->fetch("./partials/header.php", [
  "title" => "Clínica Asotrauma | Estadísticas"
]) ?>
<main class="container py-4">
  <section class="d-md-grid gap-3 justify-content-start mb-5" id="general-summary"> </section>

  <div class="d-lg-grid gap-4" style="grid-template-columns: 1fr 1fr;">
    <?= $this->fetch("./home/partials/facturacion.php") ?>
    <?= $this->fetch("./home/partials/admisiones.php") ?>
  </div>
  <span x-data="iniciarGraficas"></span>
</main>