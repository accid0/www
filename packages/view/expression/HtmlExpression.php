<?php

namespace packages\view\expression;

use packages\models\factory\AbstractFactory;

use packages\models\observer\Observable;

class HtmlExpression extends Expression {
  function __construct($str = '', Observable $obl){
    parent::__construct($str,$obl);
  }
	
}