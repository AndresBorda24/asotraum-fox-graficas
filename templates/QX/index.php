<?= $this->fetch("./partials/header.php", [
  "title" => "Gráfias estadísticas | Quirófano",
  "extra" => "./QX/partials/fechas-handler.php"
]) ?>

<main class="py-4 mx-auto px-3 p-md-4 d-flex flex-column gap-4" style="max-width: 1200px;">
  <div class="d-flex flex-column gap-4 d-md-grid" style="grid-template-columns: 1fr 1fr;">
    <?= $this->fetch("./QX/partials/general.php") ?>
    <?= $this->fetch("./QX/partials/ocupacion.php") ?>
  </div>
  <?= $this->fetch("./QX/partials/motivos-cancelacion.php") ?>
  <?= $this->fetch("./QX/partials/medicos.php") ?>
</main>