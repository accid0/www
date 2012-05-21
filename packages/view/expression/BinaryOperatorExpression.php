<?php
namespace packages\view\expression;

use packages\view\plugins\DumpObserver;

use packages\models\factory\AbstractFactory;

use packages\models\observer\Observable;

use packages\view\expression\Expression;

use packages\models\storage\FactoryStorage;

use ArrayObject;

class BinaryOperatorExpression extends Expression {
  /**
   * 
   * Enter description here ...
   * @var Storage
   */
  private $left_var;
  /**
   * 
   * Enter description here ...
   * @var Storage
   */
  private $right_var;
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $operator;
  /**
   * @return Storage
   * Enter description here ...
   */
  function getL_Var() {
    return $this->left_var;
  }
  /**
   * @return Storage
   * Enter description here ...
   */
  function getR_Var() {
    return $this->right_var;
  }
  /**
   * @return string
   * Enter description here ...
   */
  function getOperator(){
    return $this->operator;
  }
  /**
   * @return void
   * Enter description here ...
   * @param string $str
   * @param Observable $obl
   */
  function __construct($str = '', Observable $obl) {
    parent::__construct($str, $obl);
    $this->attach(new DumpObserver());
  }
  /**
   * @return void
   * Enter description here ...
   * @param Expression $exnl
   * @param string $op
   * @param Expression $exnr
   */
  function addVar(Expression $exnl,$op, Expression $exnr) {
    $this->left_var = $exnl; 
    $this->operator = trim( $op );
    $this->right_var = $exnr; 
  }
  /**
   * @return void
   * Enter description here ...
   * @param Expression $exn
   */
  function removeVar(Expression $exn) {
    if ($exn === $this->left_var) 
      $this->left_var = AbstractFactory::getInstance('packages\view\factory\FactoryStorage')->getObject('Null');
    elseif ( $exn === $this->right_var ) 
      $this->right_var = AbstractFactory::getInstance('packages\view\factory\FactoryStorage')->getObject('Null');
  }	
}