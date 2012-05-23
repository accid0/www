<?php
/**
*@name BinaryOperatorObserver.php
*@packages models
*@subpackage 
*@author Andrew Scherbakov
*@version 1.0
*@copyright created 23.05.2012
*/
namespace packages\view\plugins;

use packages\models\observer\Observable;

use packages\view\expression\HtmlExpression;

use packages\view\expression\Expression;

use packages\models\observer\Observer;

class BinaryOperatorObserver implements Observer{
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
   * Метод выполняется при поступленни уведомления с обьекта акцептора
   * @param subject Observable <p> Обьект, который связан с акцептором данного наблюдателя,
   * может иметь своих наблюдателей , параметры которых могут понадобиться данному наблюдателю.
   * @todo
   * 	-Этот метод может проводить проверки и вызывать метод init 
   * @see PluginObserver::init()
   * @see Storage::notify()
   * @return void 
   */
  public function update (Observable $subject){
  }
  /**
   * @see PluginObserver::update()
   * @todo
   * 	-Инициализацию данных плагина проводить в этом методе или в дочернем
   * @param Observer $obs
   * @return void
   */
  public function init (Observer $obs){
  }
  /**
   * 
   * Enter description here ...
   */
  function __construct(){
  }
  /**
   * @return void
   * @param mixed $left
   * @param string $op
   * @param mixed $right
   * @param Expression $exn
   */
  function addVars($left, $op, $right, Expression $exn) {
    if ( !($left instanceof  Expression))
      $left = new HtmlExpression( $left, $exn);
    if ( !($right instanceof  Expression))
      $right = new HtmlExpression( $right, $exn);
    $this->left_var = $left;
    $this->operator = $op;
    $this->right_var = $right;
  }
  /**
   * @param Expression $exn
   * @return void
   */
  function cleanVars(Expression $exn) {
    $this->left_var = NULL;
    $this->operator = '';
    $this->right_var = NULL;
  }
  /**
   * @param Expression $exn
   * @return Expression
   * Enter description here ...
   */
  function getL_Var(Expression $exn) {
    return $this->left_var;
  }
  /**
   * @param Expression $exn
   * @return Expression
   * Enter description here ...
   */
  function getR_Var(Expression $exn) {
    return $this->right_var;
  }
  /**
   * @param Expression $exn
   * @return string
   * Enter description here ...
   */
  function getOperator(Expression $exn){
    return $this->operator;
  }
  /**
   * 
   * @param Expression $exn
   * @return array
   */
  function getVars(Expression $exn){
    $result = array();
    if ( !empty( $this->left_var) && !empty( $this->right_var))
      $result [$this->left_var->expression()]= $exn->getDumpResult( 'result');
    return $result;
  }
}