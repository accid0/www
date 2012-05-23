<?php
namespace packages\view\expression;

use packages\view\plugins\BinaryOperatorObserver;

use packages\view\plugins\DumpObserver;

use packages\models\factory\AbstractFactory;

use packages\models\observer\Observable;

use packages\view\expression\Expression;

use packages\models\storage\FactoryStorage;

use ArrayObject;

class BinaryOperatorExpression extends Expression {
  /**
   * @return void
   * Enter description here ...
   * @param string $str
   * @param Observable $obl
   */
  function __construct($str = '', Observable $obl) {
    parent::__construct($str, $obl);
    $this->attach(new DumpObserver);
    //$this->attach( new BinaryOperatorObserver);
  }	
}