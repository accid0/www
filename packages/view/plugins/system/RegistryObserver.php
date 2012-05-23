<?php
namespace packages\view\plugins\system;
use packages\models\observer\Observer;

use packages\view\expression\TemplateExpression;

use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use ArrayObject,Exception;

class RegistryObserver extends PluginObserver {
  /**
   * 
   * Enter description here ...
   * @var ArrayObject
   */
  private $objects = NULL;
  /**
   * 
   * Enter description here ...
   * @var ArrayObject
   */
  private $funcs = NULL;
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
	protected function doExecute( Expression $subject){
	  $name = $subject->getDumpResult('name');
	  if ( isset( $this->objects[$name])){
        $this->setResult($this->$name);
	  }
	  else{
  	    $plugin = $this->getPlugin($this->$name);
  	    $this->setResult(call_user_func_array( array( &$plugin, 'execute'), 
  	      func_get_args()));
	  }
	}

	/**
	 * @return void
	 */
	function __construct(){
	  parent::__construct();
	}
	/**
	 * 
	 * Enter description here ...
	 * @param string $name
	 * @param string $body
	 */
	function setFunction( $name, $alias){
	  $this->ensure( isset( $this->$name),"Конфликт имен псевдонимов");
      if ( is_null($this->funcs) )
        $this->funcs = new ArrayObject(
          array(), ArrayObject::STD_PROP_LIST);
      $this->funcs[$name] = $alias;
	}
  /**
   * @return mixed
   * @param string $key
   */
  function __get( $key ){
    $this->ensure( empty($key), "Ключ не можеь быть пустым");
    $this->ensure(
    	 !isset($this->objects[$key]) && !isset($this->funcs[$key]) ,
    	 "Не валидный параметр запроса [$key]");
    if ( isset($this->objects[$key]))
      return $this->objects[$key];
    else
      return $this->funcs[$key];
  }
  /**
   * 
   * @param string $key
   * @param mixed $value
   */
  function __set( $key , $value ){
	$this->ensure( isset( $this->$name),"Конфликт имен псевдонимов");
    if ( is_null($this->objects) )
      $this->objects = new ArrayObject(
        array(), ArrayObject::STD_PROP_LIST);
    $this->objects[$key] = $value;
  }
  /**
   * @return bool
   * @param string $key
   */
  function __isset ( $key ){
    return (isset($this->objects[$key]) || isset($this->funcs[$key]));
  }
}