<?php
namespace packages\models\application;
use Iterator, ArrayAccess, ReflectionClass,Exception;
class ApplicationHelperIterator  implements Iterator, ArrayAccess {
  /**
   * 
   * Enter description here ...
   * @var int
   */
  private $position = 0;
  /**
   * 
   * Enter description here ...
   * @var array
   */
  private $array = array();
  /**
   * 
   * Enter description here ...
   * @param bool $exn
   * @param string $msg
   * @throws Exception
   */
  private function ensure( $exn, $msg){
    if ($exn) throw new Exception($msg);
  }
  /**
   * 
   * Enter description here ...
   * @param ApplicationHelperIterator $obj
   */
  private static function merge( self $destin, self $resource){
    foreach ($resource as $obj){
      $destin->add( $obj);
    }
  }
  /**
   * 
   * Enter description here ...
   * @param array $array
   */
  function __construct( $array = array()){
    $this->array = $array;
  }
  /**
   * 
   * Enter description here ...
   */
  function rewind() {
    $this->position = 0;
  }
  /**
   * @return Helper
   */
  function current() {
    return $this->array[$this->position];
  }
  /**
   * @return int
   */
  function key() {
    return $this->position;
  }
  /**
   * 
   */
  function next() {
    ++$this->position;
  }
  /**
   * @return bool
   */
  function valid() {
    return isset($this->array[$this->position]);
  }
  /**
   * 
   * Enter description here ...
   * @param int $offset
   * @param mixed $value
   */
  public function offsetSet($offset, $value) {
    if (!is_null($offset)) {
      $this->array[0][$offset] = $value;
    }
  }
  /**
   * 
   * Enter description here ...
   * @param int $offset
   * @return bool
   */
  public function offsetExists($offset) {
    return isset($this->array[$offset]);
  }
  /**
   * 
   * Enter description here ...
   * @param int $offset
   */
  public function offsetUnset($offset) {
    unset($this->array[$offset]);
  }
  /**
   * 
   * Enter description here ...
   * @param int $offset
   * @return Helper
   */
  public function offsetGet($offset) {
    return isset($this->array[$offset]) ? $this->array[$offset] : null;
  }
  /**
   * 
   * Enter description here ...
   * @param string $key
   * @return ApplicationHelperIterator
   */
  public function __get( $key){
    return $this->array[0]->$key;
  }
  /**
   * 
   * Enter description here ...
   * @param string $name
   * @param array $arguments
   * @return mixed
   */
  public function __call( $name, $arguments){
    try{
      $class = new ReflectionClass($this->array[0]);
    }
    catch (Exception $e){
      $this->ensure(TRUE, "Невозможно получить класс объекта");
    }
    $this->ensure( !$class->hasMethod($name), 
    	"В классе не доступен метод [$name]");
    $method = $class->getMethod($name);
    return $method->invokeArgs($this->array[0],$arguments);
  }
  /**
   * 
   * Enter description here ...
   * @return string
   */
  public function __toString(){
    return (string)$this->array[0];
  }
  /**
   * 
   * Enter description here ...
   * @param mixed $key
   * @return bool
   */
  public function __isset( $key){
    return isset( $this->array[0]->$key);
  }
  /**
   * 
   * Enter description here ...
   * @return int
   */
  public function count(){
    return count($this->array);
  }
  /**
   * 
   * Enter description here ...
   * @param Helper $obj
   */
  public function add( Helper $obj){
    $this->array []= $obj;
  }
  /**
   * 
   * @param string $tag
   */
  public function findTree( $tag, $attr = NULL, $value = NULL){
    $result = new self;
    foreach ($this->array as $obj){
      self::merge($result, $obj->findTree( $tag, $attr, $value));
    }
    return $result;
  }
}