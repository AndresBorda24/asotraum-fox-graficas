<?php
declare(strict_types=1);

use App\Config;
use App\ConnectionFox;

return [
    Config::class        => fn() => new Config(
        require __DIR__ . "/configs.php"
    ),
    ConnectionFox::class => fn(Config $c) => new ConnectionFox(
        $c->get("db.source")
    )
];
