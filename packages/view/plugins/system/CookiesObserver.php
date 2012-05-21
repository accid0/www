<?php
namespace packages\view\plugins\system;
use packages\models\observer\Observer;

use packages\view\expression\Expression;

use packages\view\plugins\PluginObserver;

use ArrayObject, Exception;
class CookiesObserver extends PluginObserver {
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
    $this->setResult( new ArrayObject(
          $_COOKIE, ArrayObject::STD_PROP_LIST));
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::doExecute()
   */
  protected function doExecute( Expression $subject){
    
  }
  /**
   * 
   */
  function __construct(){
    parent::__construct();
  }
  /**
   * @return mixed
   * @param string $key
   * @throws Exception
   */
  function __get( $key ){
    $cookies = $this->getResult();
    $this->ensure( !isset($cookies[$key]) ,
    	"Куки с именем [ $key] не существует");
    return $cookies[$key];
  }
  /**
   * 
   * Enter description here ...
   * @param string $key
   * @param mixed $value
   */
  function __set( $key , $value){
    $cookies = $this->getResult();
    $cookies [$key]= $value;
    setcookie( $key , $value);
  }
  /**
   * @return bool
   * @param string $key
   */
  function __isset ( $key ){
    $cookies = $this->getResult();
    return isset($cookies[$key]);
  }
}