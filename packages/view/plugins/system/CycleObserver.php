<?php
namespace packages\view\plugins\system;

use packages\models\observer\Observer;

use packages\view\expression\TemplateExpression;
use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use ArrayObject;

class CycleObserver extends PluginObserver {
  /**
   * 
   * @var array
   */
  private $cv = array();
  /**
   * @var int
   */
  private $pointer = array();
  /**
   * @var int
   */
  private $total = array();
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::install()
   */
  protected function install(){
    
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::init()
   */
  function init(Observer $obs){
  }
	/**
	 * @return string
	 * @param Expression $subject
	 */
	protected function doExecute(Expression $subject){
	  $values = '';
	  $body = '';
	  foreach ( $subject->getVars() as $key=>$vl){
	    if ( strpos($key , "__equal$") !== FALSE && 
	      $vl->getL_Var()->expression() == 'values' ){
	        $values = $vl->getDumpResult('result');
	        $body = $vl->getR_Var()->expression();
	    }
	  }
	  $this->ensure(is_null($values), "Не найден параметр values");
	  if ( isset($this->cv[$body])){
	    if ( ++$this->pointer[$body] >= $this->total[$body] )
	      $this->pointer[$body] = 0;
	    $this->setResult(
	      $this->cv[$body][$this->pointer[$body]]);
	  }
  	  elseif ( is_string($values)) {
  	    $this->cv[$body] = preg_split ("@\,@", $values);
  	    $this->total[$body] = count($this->cv[$body]);
  	    $this->pointer[$body] = 0;
	    $this->setResult(
	      $this->cv[$body][$this->pointer[$body]]);
  	  }
  	  else{
  	    $this->cv[$body] = $values;
  	    $this->total[$body] = count($this->cv[$body]);
  	    $this->pointer[$body] = 0;
	    $this->setResult(
	      $this->cv[$body][$this->pointer[$body]]);
  	  }
	}

	/**
	 * @return void
	 */
	function __construct(){
	  parent::__construct();
	}
}