<?php
/**
*@name ArgumentsObserver.php
*@packages models
*@subpackage plugins
*@author Andrew Scherbakov
*@version 1.0
*@copyright created 23.05.2012
*/
namespace packages\view\plugins;

use packages\view\expression\HtmlExpression;

use packages\view\expression\BinaryOperatorExpression;

use packages\models\observer\Observer;

use packages\models\observer\Observable;

use packages\view\expression\Expression;

use ArrayObject;

class ArgumentsObserver implements Observer{
  /**
   * 
   * Enter description here ...
   * @var array;
   */
  private $vars = array();
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
   * @param mixed $args
   * @param Expression $exn
   */
  function addVars($args, Expression $exn) {
    if ( $args instanceof  Expression)
      $this->vars['__var$' . $args->getId()] = $args;
    else{
      if ( !is_array( $args) && !( $args instanceof  ArrayObject))
        $args = array( $args);
      foreach ( $args as $key => $arg){
        $v = new BinaryOperatorExpression('', $exn);
        $v->addVars( $key, '=', '');
        $v->setDumpResult( 'result', $arg);
        $this->vars['__equal$' . $v->getId()] = $v;
      }
    }
  }
  /**
   * @param Expression $exn
   * @return void
   */
  function cleanVars(Expression $exn) {
    $this->vars = array();
  }
  /**
   * @param Expression $exn
   * @return array
   */
  function getVars(Expression $exn) {
    return $this->vars;
  }
  /**
   * @param Expression $exn
   * @return array
   */
  function varsToArray( Expression $exn){
    $result = array();
    foreach ($this->vars as $var){
      $result = array_merge($result, $var->getVars());
    }
  }
}