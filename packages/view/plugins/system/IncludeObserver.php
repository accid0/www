<?php
namespace packages\view\plugins\system;
use packages\models\observer\Observer;

use packages\view\expression\TemplateExpression;
use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use ArrayObject,Exception;

class IncludeObserver extends PluginObserver {
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
	  $file = '';
	  foreach ( $subject->getVars() as $key=>$vl){
	    if ( strpos($key , "__equal$") !== FALSE && 
	      $vl->getL_Var()->expression() == 'file' ){
	      $file = $vl->getDumpResult('result');
	    }
	  }
	  if ( !is_null($file)){
	    $text = file_get_contents(
	      $this->getApplicationHelper()->templateFolder . 
	      $file);
	    $this->setResult($text, $file);
	  }
	  else
	    $this->ensure(FALSE, "Не найден параметр file");
	}

	/**
	 * @return void
	 */
	function __construct(){
	  parent::__construct();
	}
}