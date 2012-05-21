<?php
namespace packages\models\factory;

use ReflectionClass,ArrayObject;
use packages\models\exception\AbstractInstanceException;
use packages\models\exception\AbstractObjectException;

abstract class AbstractFactory {
  /**
	* @staticvar
	* @var AbstractFactory
	*/
  private static $instance;
  /**
   * 
   * @var mixed
   */
  private $objects;
  /**
   * 
   * Enter description here ...
   */
  private final function __construct(){
    $this->objects =  new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
  }
  /**
   * 
   * Enter description here ...
   */
  protected final function __clone() { /* ... */ }
  /**
   * 
   * Enter description here ...
   */
  protected final function __wakeup() { /* ... */ }
  /**
   * @static
   * Enter description here ...
   * @param string $str
   * @return AbstractFactory
   */
  public static function getInstance($str = ''){
    if ( is_null(self::$instance) ) {
      self::$instance =  new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
    }
    if ( !isset(self::$instance[$str])) {
      try{
        $class = new ReflectionClass($str);
      }
      catch ( Exception $e ){
        throw new AbstractInstanceException("Невозможно создать класс объекта {". $str ."}");
      }
      if ($class->isSubclassOf("packages\\models\\factory\\AbstractFactory"))
        self::$instance [$str]= new $str;
      else
        throw new AbstractInstanceException("Класс {". $str ."} должен наследоваться от AbstractFactory");
    }
    return self::$instance[$str];
  }
  /**
   * 
   * Enter description here ...
   * @param string $str
   * @return mixed
   */
  function getObject($str){
    if ( !isset($this->objects[$str])) {
      try{
        $class = new ReflectionClass($str);
      }
      catch ( Exception $e ){
        throw new AbstractObjectException("Невозможно создать класс объекта {". $str ."}");
      }
      $this->objects[$str] = new $str;
    }
    return $this->objects[$str];
  }
  /**
   * 
   * Enter description here ...
   * @param string $str
   * @return bool
   */
  protected function isObject( $str){
    return isset( $this->objects[$str]);
  }
  /**
   * 
   * Enter description here ...
   * @param string $str
   * @param mixed $obj
   */
  protected function registerObject( $str, $obj){
    $this->objects [$str]= $obj; 
  }
}