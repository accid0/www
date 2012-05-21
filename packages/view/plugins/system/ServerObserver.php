<?php
namespace packages\view\plugins\system;
use packages\models\observer\Observer;

use packages\view\expression\Expression;

use packages\view\plugins\PluginObserver;

use ArrayObject;
class ServerObserver extends PluginObserver {
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
  function init( Observer $obs){
        $this->setResult( new ArrayObject(
          $_SERVER, ArrayObject::STD_PROP_LIST));
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
   */
  function __get( $key ){
    $properties = $this->getResult();
    $this->ensure( !isset($properties[$key]) ,
      "Не валидный параметр запроса $key");
    return $properties[$key];
  }
  /**
   * @return boolean
   * @param string $key
   */
  function __isset ( $key ){
    $properties = $this->getResult();
    return isset($properties[$key]);
  }
}