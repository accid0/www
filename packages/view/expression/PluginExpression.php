<?php

namespace packages\view\expression;

use packages\view\plugins\ArgumentsObserver;

use packages\view\plugins\DumpObserver;

use packages\models\observer\FactoryObserver;

use packages\models\observer\Observable;

use ArrayObject;

class PluginExpression extends Expression implements Template{
  /**
   * @return void
   * Enter description here ...
   * @param string $str
   */
  function __construct($str = '', Observable $obl) {
    parent::__construct($str, $obl);
    $this->attach(new DumpObserver());
    //$this->attach( new ArgumentsObserver());
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\expression.Template::prepareFileTemplate()
   */
  function prepareFileTemplate($query){
    $result = '';
    $this->setQuery( $query);
    if ( isset($this->templates[$query])){
      $this->setFileName( $this->templates[$query]);
      $result = $this->templates[$query];
    }
    else{ 
      $this->setFileName( $result);
    }
    return $result;
  }
}