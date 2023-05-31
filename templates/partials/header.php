<header class="p-2 mb-4 shadow bg-body">
  <div class="container d-flex border-bottom align-items-center mb-0">
    <img
    class="m-2" src="/img/logo_aso.png"
    alt="logo-aso"
    height="30">
    <span class="m-0 fs-4">Cl&iacute;nica Asotrauma</span>
  </div>
  <nav class="container d-flex justify-content-center flex-wrap small gap-1">
    <a
    class="btn btn-sm btn-outline-dark rounded-0 rounded-bottom shadow-sm
    <?= $this->isRoute("ventas") ? 'active' : '' ?>"
    href="<?= $this->link('ventas') ?>">Ventas</a>
  </nav>
</header>
