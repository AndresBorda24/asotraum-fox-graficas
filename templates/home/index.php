<?= $this->fetch("./partials/header.php", [
  "title" => "Clínica Asotrauma | Estadísticas"
]) ?>
<main class="container py-4">
  <section class="d-flex px-2 pb-4 gap-3 justify-content-start mb-4" id="general-summary">
    <?= $this->fetch("./home/partials/admisiones.php") ?>
  </section>

  <div class="d-flex flex-column d-lg-grid gap-4 gap-lg-5" style="grid-template-columns: 1fr 1fr;">
    <?= $this->fetch("./home/partials/admisiones-summary.php") ?>
    <?= $this->fetch("./home/partials/facturacion.php") ?>
    <?= $this->fetch("./home/partials/qx-summary.php") ?>
  </div>
  <span x-data="iniciarGraficas"></span>
</main>