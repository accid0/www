<?php
namespace packages\models\db;
use Iterator, Exception;

class ColumnsInfoCollection implements Iterator {
  private $position = 0;
  private $keys = array();
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $table = NULL;
  /**
   * 
   * Enter description here ...
   * @var array
   */
  private $fields = array();
  /**
   * 
   * Enter description here ...
   * @param mixed $exp
   * @param string $msg
   * @throws Exception
   */
  private function ensure( $exp, $msg){
    if ( $exp) throw new Exception($msg);
  }
  /**
   * 
   * Enter description here ...
   * @param array $array
   */
  private function init( $array){
    list($this->table) = array_keys( $array);
    $fields = $array[ $this->table];
    $this->ensure( is_null( $this->table) || !is_array( $fields) || empty($fields),
    	"[ColumnsInfoCollection]: не правильно задан массив [" .
        var_export($fields, TRUE) . "]");
    foreach ( $fields as $key => $field){
      if ( !is_array( $field)){
        $this->fields [$key]= new Column( $this->table, $field);
      }
      else {
        $newtable = array( $key => $field);
        $this->fields [$key]= new self( $newtable);
      }
    }
  }
  /**
   * 
   * Enter description here ...
   * @param array $array
   */
  function __construct( $array) {
    $this->ensure( !is_array($array), 
    	"[DbTableCoumnsCollection]: конструктор принимает только массивы");
    $this->init( $array);
    $this->keys = array_keys( $this->fields);
  }
  /**
   * 
   * Enter description here ...
   * @return string
   */
  function getTable(){
    return $this->table;
  }
  /**
   * @return array
   * Enter description here ...
   */
  function getArray(){
    $array = array();
    foreach ( $this->fields as $key => $field){
      if ($field instanceof  self) {
        $array [$key]= $field->getArray();
      }
    }
    return $array;
  }
  /**
   * @return string
   * Enter description here ...
   */
  function getGraph(){
    $graph = $this->getArray();
    return addslashes( json_encode($graph));
  }
  /**
   * 
   * Enter description here ...
   * @param string $key
   */
  function __get( $key){
    $this->ensure( !isset( $this->fields[$key]), 
    	"[DbTableCoumnsCollection]:[$key]:Нет такой колонки");
    return $this->fields[$key];
  }
  /**
   * 
   * Enter description here ...
   */
  function rewind(){
    $this->position = 0;
    foreach ($this->fields as $field){
      if ( $field instanceof self) $field->rewind();
    }
  }
  /**
   * @return mixed
   * Enter description here ...
   */
  function current(){
    if ( $this->fields[ $this->keys[ $this->position]] instanceof  self){
      return $this->fields[ $this->keys[ $this->position]]->current();
    }
    return $this->fields[ $this->keys[ $this->position]];
  }
  /**
   * @return int
   * Enter description here ...
   */
  function key(){
    if ( $this->fields[ $this->keys[ $this->position]] instanceof  self){
      return $this->fields[ $this->keys[ $this->position]]->key();
    }
    return $this->keys[ $this->position];
  }
  /**
   * 
   * Enter description here ...
   */
  function next(){
    if ( $this->fields[ $this->keys[ $this->position]] instanceof  self){
      $this->fields[ $this->keys[ $this->position]]->next();
      if ( !$this->fields[ $this->keys[ $this->position]]->valid())
        ++$this->position;
    }
    ++$this->position;
  }
  /**
   * @return boolean
   * Enter description here ...
   */
  function valid(){
    if ( $this->fields[ $this->keys[ $this->position]] instanceof  self){
      return $this->fields[ $this->keys[ $this->position]]->valid();
    }
    return isset( $this->fields[ $this->keys[ $this->position]]);
  }
}