<?php
/**
*@name Column.php
*@packages models
*@subpackage db
*@author Andrew Scherbakov
*@version 1.0
*@copyright created 22.05.2012
*/
namespace packages\models\db;

class Column {
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $name;
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $table;
  /**
   * 
   * Enter description here ...
   * @param string $name
   */
  function __construct( $table, $name){
    $this->table = (string)$table;
    $this->name = (string)$name;
  }
  /**
   * @return string
   * Enter description here ...
   */
  function __toString(){
    return "{$this->table}.{$this->name}";
  }
}