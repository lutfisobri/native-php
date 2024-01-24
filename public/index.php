<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Riyu\Foundation\Application;
use Riyu\Handler\Error;

$context = Application::getInstance();

$context->setBasePath(dirname(dirname(__FILE__)));

new Error($context);

$context->run();
