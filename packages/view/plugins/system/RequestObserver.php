<?php
namespace packages\view\plugins\system;
use packages\models\observer\Observer;

use packages\view\expression\Expression;

use packages\view\plugins\PluginObserver;

use ArrayObject, Exception;
class RequestObserver extends PluginObserver {
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
    if ( isset($_SERVER["REQUEST_METHOD"]) ){
        $this->setResult( new ArrayObject(
          array_merge( $_GET, $_POST), ArrayObject::STD_PROP_LIST));
    }
    else{
      foreach ( $_SERVER["argv"] as $arg){
        if ( strpos($arg, "=") ){
          list( $key , $val ) = explode("=", $arg);
          $this->$key = $val;
        }
      }
    }
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
    if ( !isset($properties[$key]) )
      throw new Exception("В данных запроса нет параметра [$key]");
    return $properties[$key];
  }
  /**
   * @return bool
   * @param string $key
   */
  function __isset ( $key ){
    $properties = $this->getResult();
    return isset($properties[$key]);
  }
}