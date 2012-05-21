<?php

namespace packages\view\expression;

use packages\view\plugins\DumpObserver;

use packages\models\observer\FactoryObserver;

use packages\models\observer\Observable;

use ArrayObject;

class PluginExpression extends Expression implements Template{
  /**
   * 
   * Enter description here ...
   * @var array;
   */
  private $vars = array();
  /**
   * @return array
   * Enter description here ...
   */
  function getVars() {
    return $this->vars;
  }
  /**
   * @return void
   * Enter description here ...
   * @param string $str
   */
  function __construct($str = '', Observable $obl) {
    parent::__construct($str, $obl);
    $this->attach(new DumpObserver());
    $this->vars = array();
  }
  /**
   * @return void
   * Enter description here ...
   * @param Expression $exn
   */
  function addVar($str, Expression $exn) {
    $this->vars [$str]= $exn; 
  }
  /**
   * @return void
   */
  function cleanVars() {
    $this->vars = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
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