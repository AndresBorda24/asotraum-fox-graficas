<header class="bg-primary">
  <div class="container p-3 d-flex align-items-center justify-content-between">
    <a href="/graficas">
      <img
      height="25"
      src="https://asotrauma.com.co/wp-content/uploads/2021/08/logo-asotrauma-w.svg"
      alt="logo-blanco">
    </a>
  </div>
</header>
<div class="bg-secondary text-light shadow sticky-top z-1">
  <div
    class="container d-flex flex-wrap gap-2 flex-column flex-md-row justify-content-between nav-scroller p-1 align-items-center"
  >
    <span class="fs-5">
      <?=  $title ?? "Cl&iacute;nica Asotrauma" ?>
    </span>

    <?php
      if (isset($extra)) {
        echo $this->templateExists($extra)
          ? $this->fetch($extra)
          : $extra;
      }
    ?>
  </div>
</div>