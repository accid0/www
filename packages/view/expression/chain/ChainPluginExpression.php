<?php

namespace packages\view\expression\chain;

use packages\view\plugins\DumpObserver;

use packages\view\expression\BinaryOperatorExpression;

use packages\models\observer\Observable;

class ChainPluginExpression extends BinaryOperatorExpression {
  /**
   * 
   * Enter description here ...
   * @param string $str
   * @param Observable $obl
   */
  function __construct($str = '', Observable $obl) {
    parent::__construct($str,$obl);
  }
}