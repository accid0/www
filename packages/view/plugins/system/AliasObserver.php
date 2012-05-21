<?php
namespace packages\view\plugins\system;
use packages\models\observer\Observer;

use packages\models\factory\AbstractFactory;

use packages\view\expression\TemplateExpression;
use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use ArrayObject,Exception;

class AliasObserver extends PluginObserver {
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
	  $plugin = '';
	  foreach ( $subject->getVars() as $key=>$vl){
	    if ( strpos($key , "__equal$") !== FALSE && 
	      $vl->getL_Var()->expression() == 'name' ){
	        $name = $vl->getDumpResult('result');
	    }
	    elseif ( strpos($key , "__equal$") !== FALSE && 
	      $vl->getL_Var()->expression() == 'plugin' ){
	        $plugin = $vl->getDumpResult('result');
	    }
	  }
	  if ( $name != '' && $plugin != ''){
  	   $this->getPlugin('registry') ->setFunction($name , $plugin);
	  }
	  else
	    $this->ensure(TRUE, "Ошибка ввода псевдонима функции");
	}

	/**
	 * @return void
	 */
	function __construct(){
	  parent::__construct();
	}
}