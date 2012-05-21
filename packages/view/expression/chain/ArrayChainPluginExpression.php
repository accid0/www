<?php

namespace packages\view\expression\chain;

use packages\view\plugins\DumpObserver;

use packages\view\expression\Expression;

use packages\models\observer\Observable;

class ArrayChainPluginExpression extends Expression {
  /**
   * 
   * Enter description here ...
   * @param string $str
   * @param Observable $obl
   */
  function __construct($str = '', Observable $obl) {
    parent::__construct($str,$obl);
    $this->attach(new DumpObserver());
  }
}