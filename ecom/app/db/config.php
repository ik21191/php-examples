<?php
require_once __DIR__ . '/../helpers/EnvLoader.php';

$envLoader = EnvLoader::getInstance();

define("DB_HOST", $envLoader->getProperty("DB_HOST"));
define("DB_USERNAME", $envLoader->getProperty("DB_USERNAME"));
define("DB_PASSWORD", $envLoader->getProperty("DB_PASSWORD"));
define("DB_DATABASE_NAME", $envLoader->getProperty("DB_DATABASE_NAME"));