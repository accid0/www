<?php
namespace packages\models\db;

use xPDOObject;
use Iterator, Exception;
class xPDOCollection implements Iterator {
  /**
   * 
   * Enter description here ...
   * @var array|xPDOObject
   */
  private $data;
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $graph;
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $table;
  /**
   * 
   * Enter description here ...
   * @var int
   */
  private $pointer = 0;
  /**
   * 
   * Enter description here ...
   * @var boolean
   */
  private $initRelated = FALSE;
  /**
   * 
   * Enter description here ...
   * @param boolean $exp
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
   * @param string $key
   */
  private function arrayKeySearch( $array, $key){
    foreach ( $array as $k => $v){
      if ( $k === $key) return TRUE;
      elseif ( is_array( $v) && $this->arrayKeySearch( $v, $key))
        return TRUE;
    }
    return FALSE;
  }
  /**
   * 
   * Enter description here ...
   * @param xPDOObject $data
   * @param string $key
   * @return mixed
   */
  private function getRelatedObject( xPDOObject $data, $key){
    $result = $data->$key;
    if ( empty( $result)){
      $fk = $data->getFKDefinition( $key);
      if ($fk['cardinality'] == 'many')
        $result = $data->getMany( $key);
      else  $result = $data->getOne( $key);
    }
    return $result;
  }
  /**
   * 
   * Enter description here ...
   * @param xPDOObject $data
   * @param string $sTable
   * @param array $aColumns
   * @param array $graph
   * @return array|string
   */
  private function getRow(xPDOObject $data, $sTable, $aColumns, $graph){
    $row = array();
    foreach ( $aColumns as $col){
      $field = str_replace( "$sTable.", '', $col);
      if ( $field !== $col){
        $row[]= $data->$field;
      }
      else{
        $table = explode( ".", $col);
        $table = $table[0];
        $columns = array();
        $columns []= $col;
        $g = array();
        $t = '';
        foreach ( $graph as $key => $node){
          if ( $key === $table){
            $g = $node;
            $t = $key;
            break;
          }
          elseif ( $this->arrayKeySearch( $node, $table)) {
            $g = $node;
            $t = $key;
            break;
          }
        }
        $this->ensure( $t== '', "[xPDOCollection::$sTable] Ошибка в графе");
        $row[]= $this->parse( $this->getRelatedObject($data, $t), $t, $columns,
          $g );
      }
    }
    if ( count($row) <=1) return $row[0];
    else return $row;
  }
  /**
   * 
   * Enter description here ...
   * @param mixed $data
   * @param string $sTable
   * @param array $aColumns
   * @param array $graph
   * @return array
   */
  private function parse( $data, $sTable, $aColumns, $graph){
    $result = array();
	if ( is_array( $data)){
	  foreach ( $data as $d){
	    $result []= $this->getRow( $d, $sTable, $aColumns, $graph);
	  }
    }
	else{
	  $result = $this->getRow( $data, $sTable, $aColumns, $graph);
	}
	return $result;
  }
  /**
   * 
   * Enter description here ...
   * @param string $table
   * @param string $graph
   * @param array|xPDOObject $data
   */
  function __construct( $data, $table, $graph =  '', $ir = FALSE){
    $this->ensure( !is_string($table) || $table ==='', 
    	"[xPDOCollection]: параметр [table] не строка");
    $this->ensure( !is_string($graph) , 
    	"[xPDOCollection]: параметр [graph] не строка");
    $this->table = $table;
    $this->data = $data;
    reset( $this->data);
    $this->initRelated = $ir;
    if ( $graph != '') $this->graph =  json_decode( $graph, TRUE);
    else $this->graph = array();
  }
  /**
   * 
   * Enter description here ...
   * @param array $cols
   * @return array
   */
  public function columnsToArray( $cols){
    if ( is_string( $cols))
      $cols = explode( ',', $cols );
    return $this->parse($this->data, $this->table, $cols, $this->graph);
  }
  /**
   * 
   * @return xPDOObject
   */
  function current(){
    return current( $this->data);
  }
  /**
   * @return int
   */
  function key(){
    return key( $this->data);
  }
  /**
   * 
   */
  function next(){
    next( $this->data);
  }
  /**
   * 
   */
  function rewind(){
    reset( $this->data);
  }
  /**
   * @return boolean
   */
  function valid( ){
    return key( $this->data) !== NULL;
  }
  /**
   * @return int
   * Enter description here ...
   */
  function count(){
    return count( $this->data);
  }
}