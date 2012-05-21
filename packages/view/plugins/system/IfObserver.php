<?php
namespace packages\view\plugins\system;
use packages\models\observer\Observer;

use packages\view\expression\TemplateExpression;
use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use ArrayObject,Exception;

class IfObserver extends PluginObserver {
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
	  $res = NULL;
	  $strs = array();
	  $body = $subject->getDumpResult("body");
	  foreach ( $subject->getVars() as $key=>$vl){
	    if ( strpos($key , "__binary_operator$") !== FALSE  ){
	      $res = $vl->getDumpResult('result');
	    }
	  }
	  $strs = preg_split(
	    '/\{else\}/xs', $body );
	  if ( $res){
	    if ( empty($strs)){
	      $this->setResult($body);
	    }
	    else{
	      $this->setResult($strs[0]);
	    }
	  }
	  else{
	    if ( !empty($strs) && isset( $strs[1])){
	      $this->setResult($strs[1]);
	    }
	  }
	}

	/**
	 * @return void
	 */
	function __construct(){
	  parent::__construct();
	}
}