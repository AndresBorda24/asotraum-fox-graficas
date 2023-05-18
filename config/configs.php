<?php
return [
    "app" => [
        "name" => $_ENV["APP_NAME"],
        "ver"  => $_ENV["APP_VER"],
        "base" => $_ENV["APP_PATH"],
        "url"  => $_ENV["APP_URL"]
    ],
    "db" => [
        "source" => $_ENV["FOX_SOURCE"]
    ],
    "assets" => [
        "templates"   => __DIR__ . "/.." .$_ENV["TEMPLATES"],
        "entrypoints" => __DIR__ . "/.." .$_ENV["ENTRYPOINTS_PATH"]
    ]
];
