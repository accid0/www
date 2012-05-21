<?php

namespace packages\view\expression;

use packages\view\plugins\DumpObserver;

use packages\models\factory\AbstractFactory;

use packages\models\observer\Observable;

class TemplateExpression extends Expression{
  /**
   * 
   * Enter description here ...
   * @var array
   */
  protected $templates = array();
  /**
   * 
   * Enter description here ...
   * @param string $str
   * @param Observable $obl
   */
  function __construct($str = '', Observable $obl){
    parent::__construct($str,$obl);
    $this->attach( new DumpObserver());
  }
}