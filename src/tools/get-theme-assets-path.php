<?php
require __DIR__ . '/../../public/bootstrap.php';

$app = MapasCulturais\App::i('web');
$app->init($config);

echo MapasCulturais\App::i()->view->themeFolder . '/assets/';
