<?php
use packages\models\factory\AbstractFactory;

include 'packages/autoload.php';
AbstractFactory::getInstance("packages\\models\\application\\ApplicationController")->run();