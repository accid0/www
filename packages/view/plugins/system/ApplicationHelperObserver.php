<?php
namespace packages\view\plugins\system;
use packages\models\application\ApplicationHelperIterator;

use packages\models\application\ApplicationHelper;

use packages\models\observer\Observer;

use packages\view\expression\Expression;

use packages\view\plugins\PluginObserver;

use packages\models\factory\AbstractFactory;
use Exception , SimpleXMLElement,Iterator, xPDOCacheManager;
use ReflectionClass;
class ApplicationHelperObserver extends PluginObserver {
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
    if ( is_null( $this->getResult()))
      $this->setResult( new ApplicationHelper);
  }
  /**
   * 
   * Enter description here ...
   * @param Expression $subject
   */
  protected function doExecute(Expression $subject){
    
  }
  /**
   */
  function __construct(){
    parent::__construct();
  }
  /**
   * 
   * @param string $name
   * @return ApplicationHelperIterator
   */
  function __get( $name ){
    $options = $this->getResult();
    $this->ensure( !isset($options->$name)  ,
      "Не валидное поле [$name]");
    return $options->$name;
  }
  /**
   * 
   * @param string $name
   * @return bool
   */
  function __isset ($name){
    $options = $this->getResult();
    return isset($options->$name);
  }
  /**
   * 
   * Enter description here ...
   * @param string $name
   * @param array $arguments
   * @return mixed
   */
  function __call( $name, $arguments){
    $options = $this->getResult();
    try{
      $class = new ReflectionClass($options);
    }
    catch (Exception $e){
      $this->ensure(TRUE, "Невозможно получить класс объекта");
    }
    $this->ensure( !$class->hasMethod($name), 
    	"В классе не доступен метод [$name]");
    $method = $class->getMethod($name);
    return $method->invokeArgs( $options,$arguments);
  }
}