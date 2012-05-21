<?php
namespace packages\view\plugins\system;
use packages\models\observer\Observer;

use packages\models\factory\AbstractFactory;

use packages\view\expression\TemplateExpression;
use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use ArrayObject,Exception;

class AssignObserver extends PluginObserver {
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
	  $name = '';
	  foreach ( $subject->getVars() as $key=>$vl){
	    if ( strpos($key , "__equal$") !== FALSE  ){
	        $name = $vl->getDumpResult('result');
	    }
	  }
	  if ( !is_null($name)){
  	   $this->getPlugin('registry') ->$name =  $subject->getDumpResult('body');
	  }
	  else
	    $this->ensure(FALSE, "Не найден параметр name");
	}

	/**
	 * @return void
	 */
	function __construct(){
	  parent::__construct();
	}
}