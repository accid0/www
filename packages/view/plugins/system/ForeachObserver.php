<?php
namespace packages\view\plugins\system;
use packages\models\observer\Observer;

use packages\view\factory\FactoryPluginObserver;

use packages\models\factory\AbstractFactory;

use packages\view\expression\TemplateExpression;

use packages\view\exception\ForeachObserverException;

use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use ArrayObject;
class ForeachObserver extends PluginObserver {
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
	 * @throws ForeachObserverException
	 */
	protected function doExecute(Expression $subject){
	  $res = NULL;
	  $patterns = array();
	  $repct = array();
	  $body = $subject->getDumpResult("body");
	  foreach ( $subject->getVars() as $key=>$vl){
	    if ( strpos($key , "__as$") !== FALSE  ){
	      $res = $vl->getDumpResult('result');
	    }
	  }
	  $this->ensure( is_null($res) ,
	  	"Не найден оператор 'as' в параметрах" . $subject->
	    getDumpResult('params'));
	  if ( isset($res['key']) ) {
	    $patterns [0]= "/\{" . $res['key'] . "}/";
	  }
	  if ( isset($res['value']) ) {
	    $patterns [1]= "/\{" . $res['value'] . "( \s|-|\[|\} )/xs";
	  }
	  $result = '';
	  $f = $res['value'];
	  $this->getPlugin('registry')->$f = $res['array'];
	  foreach ( $res['array'] as $k => $v){
    	if ( isset($res['key']) ) {
    	  $repct [0]= $k;
    	}
	    if ( isset($res['value']) ) {
	      $repct [1]= "{" . $res['value'] . "[" . $k . "]\\1";
	    }
	    $result .= preg_replace($patterns, $repct , $body);
	  }
	  $this->setResult($result);
	}

	/**
	 * @return void
	 */
	function __construct(){
	  parent::__construct();
	}
}