<?php

declare(strict_types=1);

namespace App;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Routing\RouteContext;
use Slim\Routing\RouteParser;
use Slim\Views\PhpRenderer;

class Views extends PhpRenderer
{
    private ?RouteInterface $route    = null;
    private ?RouteParser $routeParser = null;

    public function __construct(
        private Config $config
    ) {
        parent::__construct( $this->config->get('assets.templates') );
        $this->setLayout("layouts/main.php");
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
        if (!array_key_exists($name, $ep)) return "";

        /** Cargamos el archivo js principal */
        $tags = "<script src=\"/graficas/build/".$ep[$name]['file']."\" type=\"module\"></script>\n";

        $loadCss = function(array $cssFiles) use(&$tags) {
            foreach ($cssFiles as $file) {
                $tags .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"/graficas/build/$file\"> \n";
            }
        };

        /* Cargamos el CSS especifico para esa entrada */
        if (array_key_exists("css", $ep[$name])) $loadCss($ep[$name]["css"]);

        /* Cargamos el CSS de los archivos js que se */
        if (array_key_exists("imports", $ep[$name])) {
            $imp = $ep[$name]["imports"];
            foreach ($imp as $file) {
                if (array_key_exists("css", $ep[$file])) $loadCss($ep[$file]["css"]);
            }
        }

        return $tags;
    }

    /**
     * Setea a `route` y `routeParser`, importantes si se quieren generar
     * links
    */
    public function setRouteContext(ServerRequestInterface $request):void
    {
        $context =  RouteContext::fromRequest($request);

        $this->route = $context->getRoute();
        $this->routeParser = $context->getRouteParser();
    }

    /**
     * Genera el link para el nombre de una ruta
    */
    public function link(string $name): string
    {
        if (! isset($this->routeParser)) {
            throw new \RuntimeException(
                "No Route. You may have forgotten to use setRouteContext"
            );
        }

        return $this->routeParser->urlFor($name);
    }

    /**
     * Retorna TRUE o FALSE dependiendo si el nombre de la ruta corresponde
     * al nombre de la ruta actual
    */
    public function isRoute(string $name): bool
    {
        if (! isset($this->routeParser)) {
            throw new \RuntimeException(
                "No Route. You may have forgotten to use setRouteContext"
            );
        }

        return $this->route->getName() === $name;
    }

    /**
     * Devuelve la ruta `absoluta` para un archivo $asset
    */
    public function asset(string $asset): string
    {
        return $this->config->get("app.url") . $asset;
    }
}
