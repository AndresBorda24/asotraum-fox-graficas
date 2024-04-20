<?= $this->fetch("./partials/header.php", [
  "title" => "Gráfias estadísticas | Quirófano",
  "extra" => "./QX/partials/fechas-handler.php"
]) ?>

<main class="py-4 mx-auto px-3 p-md-4" style="max-width: 1200px;">
  <div class="d-flex flex-column gap-2 d-md-grid" style="grid-template-columns: 1fr 1fr;">
    <?= $this->fetch("./QX/partials/general.php") ?>
  </div>
</main>