<?php

declare(strict_types=1);

namespace App;

use Slim\Views\PhpRenderer;

class Views extends PhpRenderer
{
    public function __construct(
        private Config $config
    ) {
        parent::__construct( $this->config->get('assets.templates') );
    }

    /**
     * Debido a que se emplea encore para usar librerias de JS, se deben cargar
     * los archivos js y css correspondientes y las rutas estan especificadas en
     * en entrypoints.json.
     *
     * @param string $name El nombre de la carpeta donde estan los archivos en public/build
    */
    public function loadAssets(string $name): string
    {
        /* Ruta del archivo entrypoints.json */
        $_  = file_get_contents($this->config->get('assets.entrypoints'));
        $ep = @json_decode($_, true);

        /** En dado caso que la ruta no exista */
        if (!(bool) $ep) return "";

        /**
         * En el archivo de entrypoints esta folder(name)/app para
         * identificar los archivos de cada vista.
        */
        $k = $name . "/app";
        if (!array_key_exists($k, $ep["entrypoints"])) return "";

        /**
         * Tags `script` y `link`
        */
        $tags = "";

        foreach ($ep["entrypoints"][$k] as $type => $assets) {
            foreach ($assets as $asset) {
                $tags .= match ($type) {
                    'js'  => "<script src=\"$asset\" type=\"text/javascript\"></script>",
                    'css' => "<link rel=\"stylesheet\" type=\"text/css\" href=\"$asset\">",
                    default => ""
                };
            }
        }

        return $tags;
    }
}
