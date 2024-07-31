<?php

declare(strict_types=1);

use App\Kernel;

require_once 'bootstrap.php';

// (new Kernel)->execute();

$app = MapasCulturais\App::i('web');
$app->init($config);
$app->run();
