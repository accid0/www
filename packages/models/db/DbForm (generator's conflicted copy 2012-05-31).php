<?php
namespace packages\models\db;
use packages\models\factory\AbstractFactory;

use Iterator, Exception, xPDO;

use packages\models\db\DbPersistenceFactory;
class DbForm implements Iterator {
  /**
   * 
   * Enter description here ...
   * @var DbPersistenceFactory
   */
  private static $db = NULL;
  /**
   * 
   * Enter description here ...
   * @var int
   */
  private $position = 0;
  /**
   * 
   * @var string
   */
  private $json = '';
  /**
   * 
   * Enter description here ...
   * @var xPDOObject
   */
  private $object = NULL;
  /**
   * 
   * Enter description here ...
   * @var array
   */
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
   * @var string
   */
  private $fk = NULL;
  /**
   * 
   * Enter description here ...
   * @var DbForm
   */
  private $root = NULL;
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
   * @param string $msg
   */
  private function log( $msg){
    self::$db->getxPDO()->log( xPDO::LOG_LEVEL_DEBUG,
      var_export($msg, TRUE));
  }
  /**
   * 
   * Enter description here ...
   * @param array $array
   */
  private function init( $array){
    list($this->table) = array_keys( $array);
    $this->object = self::$db->newObject( $this->table);
    $fields = $array[ $this->table];
    $this->ensure( is_null( $this->table) || !is_array( $fields) || empty($fields),
    	"[DbForm]: не правильно задан массив [" .
        var_export($fields, TRUE) . "]");
    foreach ( $fields as $key => $field){
      if ( !is_array( $field)){
        $this->fields [$key]= $this->createColumn( $this->table, $field, $this->fk, $key);
      }
      else {
        $fk = $this->object->getFKDefinition( $key);
        $newtable = array( $fk['class'] => $field);
        $this->fields [$key]= new self( $newtable, $key, $this->root);
      }
    }
  }
  /**
   * 
   * Enter description here ...
   * @param string $table
   * @param string $field
   * @param string $fk
   * @param string $key
   */
  private function createColumn( $table, $field, $fk, $key){
    $column = new Column( $table, $field, $fk);
    $object = self::$db->newObject( $table);
    $pk = $object->getPk();
    
    if ( $field == $pk){
      $column->disable();
      $column->name = $key;
      return $column;
    }
    elseif ( $fk != ''){
      $query = self::$db->newQuery( $table);
      $query->select( "$pk, $field");
      $collection = self::$db->getCollection($table, $query);
      $array = $collection->columnsToArray( "{$table}.{$field}");
      $data = array();
      foreach ( $array as $item)
        $data [$item]= $item;
      $column->data = json_encode( $data);
      $column->type = 'multiselect';
      $column->onblur = 'ignore';
    }
    $serializeForm = addslashes($this->root->toJSON());
    $pk = $this->root->getPk();
    $column->name = $key;
    $column->submitdata = <<<EOF
  function (value, settings) {
  	var id = $(this.parentNode).attr("id");
  	data = {
  	    $pk : id, 
  		serializeForm : "$serializeForm"
  	}
  	return data;
  }
EOF;
    return $column;
  }
  /**
   * 
   * Enter description here ...
   * @param array $array
   */
  function __construct( $array, $fk = '', $root = NULL) {
    $this->ensure( !is_array($array), 
    	"[DbForm]: конструктор принимает только массивы");
    $this->fk = $fk;
    $this->json = json_encode($array);
    if ( is_null( $root))  $this->root = $this;
    else  $this->root =$root;
    if ( self::$db === NULL)
      self::$db = AbstractFactory::getInstance(
      	'packages\\models\\db\\DbPersistenceFactory');
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
   */
  function toJSON(){
    return $this->json;
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
   * @return string
   */
  function getPk(){
    $pk = $this->object->getPK();
    foreach ( $this->fields as $key => $value){
      if ( $pk == $value->getColumn())  return $key;
    }
    return "null";
  }
  /**
   * 
   * @param string $key
   * @return Column
   */
  function __get( $key){
    foreach ( $this->fields as $id => $field){
      if ( $field instanceof  self){
        $r = $field->$key;
        if ( $r !== NULL)  return $r;
      }
      elseif ( $id === $key)  return $field;
    }
    return NULL;
  }
  /**
   * 
   * @param string $key
   * @return boolean
   */
  function  __isset( $key){
    foreach ( $this->fields as $id => $field){
      if ( $field instanceof  self){
        if ( isset( $field->$key))  return TRUE;
      }
      elseif ( $id === $key)  return TRUE;
    }
    return FALSE;
  }
  /**
   * 
   * @param string $key
   * @param mixed $value
   */
  function __set( $key, $value){
    foreach ( $this->fields as $id => $col){
      if ( ($col instanceof self) && isset($col->$key)){
        $fk = $this->object->getFKDefinition( $id);
        if ( $fk['cardinality'] == 'many'){
          if ( !is_array( $value))  $value = array( $value);
          $objs = array();
          foreach ( $value as $item){
            if ( $ob = $col->get( $key, $item))
              $objs []= $ob;
          }
          if ( !empty( $objs))
            $this->object->addMany( $objs); 
          return;
        }
        elseif ( $fk['cardinality'] == 'one'){
          if ( $ob = $col->get( $key, $value))  $this->object->addOne( $ob);
          return; 
        }
      }
      elseif ( $id === $key){
        $f = $col->getColumn();
        $this->object->set($f, $value);
        return ;
      }
    }
  }
  /**
   * 
   * Enter description here ...
   * @param string $class
   * @param string $key
   * @param mixed $value
   * @return xPDOObject|NULL
   */
  function get( $key, $value){
    $result = NULL;
    foreach( $this->fields as $id => $col){
      if ( ( $col instanceof self) && isset( $col->$key)){
        $result = self::$db->newObject( $this->table);
        $fk = $result->getFKDefinition( $id);
        if ( $fk['cardinality'] == 'many'){
          if ( !is_array( $value))  $value = array( $value);
          $objs = array();
          foreach ( $value as $item){
            if ( $ob = $col->get( $key, $item))
              $objs []= $ob;
          }
          if ( !empty( $objs))
            $result->addMany( $objs);
          else $result = NULL; 
        }
        elseif ( $fk['cardinality'] == 'one'){
          if ( $ob = $col->get( $key, $value))
            $result->addOne( $ob);
          else $result = NULL;
        }
        $this->object = $result;
        return $result;
      }
      elseif ( $key === $id){
        $query = self::$db->newQuery( $this->table);
        $query->where( array(
          $col->getColumn() => $value
        ));
        $query->limit(1);
        $result = self::$db->getObject( $this->table, $query);
        $this->object = $result;
        return $result;
      }
    }
    return $result;
  }
  /**
   * @return xPDOObject
   */
  function save(){
    if ( !is_null( $this->object)){
      if ( !$this->object->isNew()){
        foreach ( $this->object->_relatedObjects as $key => $obj){
          if ( !empty( $obj)){
            $fk = $this->object->getFKDefinition( $key);
            $this->object->_relatedObjects [$key]= array();
            if ( $fk['cardinality'] == 'many'){
              $del = $this->object->getMany( $key);
              foreach ( $del as $d)
                $d->remove( array($this->table));
            }
            elseif ( $fk['cardinality'] == 'one'){
              $del = $this->object->getOne( $key);
            $del->remove( array($this->table));
            }
            $this->object->_relatedObjects [$key]= $obj;
          }
        }
      }
      $this->object->save();
    }
    return  $this->object;
  }
  /**
   * 
   * Enter description here ...
   */
  function delete(){
    if ( !is_null( $this->object))
      $this->object->remove();
  }
  /**
   * 
   */
  function rewind(){
    $this->position = 0;
    foreach ($this->fields as $field){
      if ( $field instanceof self) $field->rewind();
    }
  }
  /**
   * @return mixed
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