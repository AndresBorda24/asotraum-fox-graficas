<nav class="d-flex justify-content-center gap-2">
  <a
  class="btn btn-sm btn-outline-dark
  <?= $this->isRoute("ventas") ? 'active' : '' ?>"
  href="<?= $this->link('ventas') ?>">Graficas</a>
  <a
  class="btn btn-sm btn-outline-dark
  <?= $this->isRoute("ventas.grilla") ? 'active' : '' ?>"
  href="<?= $this->link('ventas.grilla') ?>">Grilla</a>
</nav>
