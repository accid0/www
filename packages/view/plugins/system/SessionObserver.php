<?php
namespace packages\view\plugins\system;
use packages\models\observer\Observer;

use packages\view\expression\Expression;

use packages\view\plugins\PluginObserver;

use Exception,ArrayObject;
class SessionObserver extends PluginObserver {
  /**
   * 
   * Enter description here ...
   * @var bool
   */
  private $access = FALSE;
  /**
   * 
   * Enter description here ...
   * @return bool
   */
  private function accessSession(){
    if ( !$this->access){
      session_cache_limiter("public");
      session_name('maria');
      session_start();
      $this->setResult(new ArrayObject(
            $_SESSION, ArrayObject::STD_PROP_LIST));
      $this->access =TRUE;
    }
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::install()
   */
  protected function install(){
    
  }
  /**
   * 
   * Enter description here ...
   */
  function __construct(){
    parent::__construct();
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::doExecute()
   */
  protected function doExecute(Expression $subject){
    
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::init()
   */
  function init( Observer $obs){
  }
  /**
   * 
   * Enter description here ...
   */
  function close(){
    if ( $this->access)
      session_write_close();
  }
  /**
   * 
   * Enter description here ...
   * @param string $name
   * @return mixed
   * @throws Exception
   */
  final function __get( $name ){
    $this->accessSession();
   $sess = $this->getResult();
   $this->ensure(!isset( $sess[$name] ) ,
      "Не валидное поле $name");
    return $sess[$name];
  }
  /**
   * 
   * Enter description here ...
   * @param string $name
   * @param mixed $value
   */
  final function __set( $name , $value){
    $this->accessSession();
    $sess = $this->getResult();
    $sess[$name] = $value;
    $_SESSION[$name] = $value;
  }
  /**
   * @return boolean
   * @param string $name
   */
  final function __isset( $name ){
    $this->accessSession();
    $sess = $this->getResult();
    return isset( $sess[$name] );
  }
}