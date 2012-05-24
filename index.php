<?php
include 'packages/autoload.php';
use packages\models\factory\AbstractFactory;
AbstractFactory::getInstance("packages\\models\\application\\ApplicationController")->run();