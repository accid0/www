<?php
namespace packages\view\factory;

use packages\models\factory\AbstractFactory;

use ArrayObject,ReflectionClass,SplFileInfo;

class FactoryStorage extends AbstractFactory{
  public function getObject ($str){
    if ($str == 'Null')
      $str = 'packages\\models\\storage\\NullStorage';
    return parent::getObject($str);

  }
}