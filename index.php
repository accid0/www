<?php
define( __ROOT_DIR__, __DIR__);
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__);
include 'packages/autoload.php';
use packages\models\factory\AbstractFactory;
AbstractFactory::getInstance("packages\\models\\application\\ApplicationController")->run();