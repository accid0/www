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
   * @param string $name
   * @param string $plugin
   */
  protected function doExecute($name, $plugin, Expression $subject){
    $this->ensure($name == '' || $plugin == '', "Ошибка ввода псевдонима функции");
  	$this->getPlugin('registry') ->setFunction($name , $plugin);
  }
  /**
   * @return void
   */
  function __construct(){
	parent::__construct();
  }
}