<?php
use Owr\App\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';
$app = AppFactory::createApp(require __DIR__ . '/../config.php');
$app->run();
