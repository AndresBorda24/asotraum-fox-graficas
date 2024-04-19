<?= $this->fetch("./partials/header.php") ?>
<?= $this->fetch("./ventas/partials/nav.php") ?>

<div
x-data="grilla"
x-bind="events"
class="small my-4 container">
  <?= $this->fetch('./partials/select-dates.php') ?>
  <a
  :href="excelUrl"
  target="_blank"
  class="btn btn-sm btn-success mt-3">Exportar a Excel</a>
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
