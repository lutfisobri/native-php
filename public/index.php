<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Riyu\Foundation\Application;

$context = Application::getInstance();

$context->setBasePath(dirname(dirname(__FILE__)));

$context->run();
