<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ventas - Grilla</title>
  <link
  rel="stylesheet"
  integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
  crossorigin="anonymous">
  <?= $this->loadAssets('ventas.grilla') ?>
</head>
<body class="bg-body-tertiary">
  <?= $this->fetch("./partials/header.php") ?>
  <?= $this->fetch("./ventas/partials/nav.php") ?>

  <h1 class="text-center mt-2">Ventas</h1>

  <div
  x-data="grilla"
  x-bind="events"
  class="small my-4 container">
    <?= $this->fetch('./partials/select-dates.php') ?>
    <a
    :href="excelUrl"
    target="_blank"
    class="btn btn-success mt-3">Exportar a Excel</a>
    <table id="datatable" class="display shadow compact small">
      <thead>
        <tr>
          <th>Tercero</th>
          <th>Nom Tercer</th>
          <th>Quien</th>
          <th>Fecha</th>
          <th>Fecha Rad.</th>
          <th>Radicaci&oacute;n</th>
          <th>Valor Factura</th>
          <th>Observaci&oacute;n</th>
        </tr>
      </thead>

    </table>
  </div>
  <?= $this->fetch("./partials/loader.php") ?>
</body>
</html>
