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
   * @param string $name
   * @param string $body
   */
  protected function doExecute($name , $body, Expression $subject){
    $this->ensure( empty($name), "Не найден параметр [name]");
  	$this->getPlugin('registry') ->$name =  $body;    
  }

	/**
	 * @return void
	 */
	function __construct(){
	  parent::__construct();
	}
}