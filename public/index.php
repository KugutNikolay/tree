<?php

use core\App;

require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/autoload.php';

$app = new App();

$app->run();