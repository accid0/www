<?php
namespace packages\view\builder;
use packages\view\expression\Expression;

use packages\models\storage\NullStorage;

use packages\view\expression\Template;

use packages\models\db\DbTableColumnsCollection;

use packages\view\expression\BinaryOperatorExpression;

use packages\view\expression\HtmlExpression;

use packages\view\expression\PluginExpression;

use packages\models\factory\AbstractFactory;

use packages\view\expression\TemplateExpression;

use packages\models\visitorer\Visitorer;

use packages\view\plugins\system\ApplicationHelperObserver;

use packages\models\application\ApplicationController;

use packages\view\plugins\PluginObserver;

use Exception, ReflectionClass, xPDO;

class NativeBuilder extends Visitorer{
  /**
   * 
   * Enter description here ...
   * @var ApplicationController
   */
  private $aC;
  /**
   * 
   * Enter description here ...
   * @var ApplicationHelperObserver
   */
  private $baseFolder;
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $tpl;
  /**
   * 
   * Enter description here ...
   * @var TemplateExpression
   */
  private $exn;
  /**
   * 
   * Enter description here ...
   * @var array
   */
  private $__data = array();
  /**
   * @return string
   * Enter description here ...
   */
  function getDumpResult(){
    return $this->tpl;
  }
  /**
   * 
   * Enter description here ...
   */
  function __construct(){
    $this->tpl = '';
    $this->__data = array();
    $this->exn = AbstractFactory::getInstance(
              'packages\\view\\factory\\FactoryStorage')->getObject('Null');
    $this->aC =AbstractFactory::getInstance(
    	"packages\\models\\application\\ApplicationController");
    $this->baseFolder = (string)$this->aC->getPlugin('applicationHelper')->
      application->templateFolder;
  }
  /**
   * 
   * Enter description here ...
   * @param TemplateExpression $exn
   * @throws Exception
   */
  function visitPluginExpression( Template $exn){
    $this->exn = $exn;
    $this->tpl = '';
    $file = $exn->getFileName();
    $class = new ReflectionClass( $exn);
    $m = $exn->getQuery();
    ob_start();
    if ( $class->hasMethod( $m)){
      $data = $exn->$m( $this);
    }
    if ( !empty($file)){
      try{
        include ( $this->baseFolder . $file);
      }
      catch ( Exception $e){
        ob_end_clean();
        throw new Exception( "[NativeBuilder::" .  $file . "] " . 
          $e->getMessage(),$e-> getCode(), $e->getPrevious());
      }
      $this->tpl = ob_get_clean();
    }
  }
  /**
   * 
   * Enter description here ...
   * @param string $name
   * @param array $arguments
   */
  public function __call($name, $arguments) {
    if ( preg_match('@visit[a-zA-Z0-9]++@xs', $name)){
      $this->visitPluginExpression( $arguments[0]);
      return;
    }
    $plugin = $this->aC->getPlugin( $name);
    $params = array();
    $body = '';
    if ( isset( $arguments[0])) $params = $arguments[0];
    if ( isset( $arguments[1])) $body = $arguments[1];
    $this->exn->cleanVars();
    //$class = new ReflectionClass( $this->exn);
    //$tpl = $class->newInstanceArgs( array( '', $this->exn));
    $tpl = $this->exn;
    if ( !is_array( $params))  $params = array( $params);
    foreach ($params as $key => $value){
      $v = new BinaryOperatorExpression( '', $tpl);
      $v->addVar( new HtmlExpression($key, $v), '=', 
        new HtmlExpression('', $v));
      $v->setDumpResult('result', $value);
      $tpl->addVar( '__equal$' . $v->getId(), $v);
    }
    $tpl->setDumpResult('body', $body);
    $tpl->setDumpResult('name', $name);
    //$tpl->attach( $plugin);
    //$this->exn->addStorage( $tpl);
    $result = $plugin->execute( $tpl);
    if ( $result instanceof  Expression){
      $temp = $this->exn;
      if ( $result->getFileName() != $temp->getFileName()){
        $tpl->addStorage( $result);
        $result->visit( $this);
        print $this->getDumpResult();
      }
      else 
        return $result->expression();
      //print $result->expression();
      $this->exn = $temp;
    }
    else return $result;
  }
  /**
   * 
   * @param string $name
   * @return mixed
   */
  public function __get( $name){
    $result = NULL;
    if ( isset( $this->__data[$name])){
      $result =  $this->__data[$name];
    }
    else{
      $class = new ReflectionClass( $this->exn);
      $class = $class->getShortName();
      $file = $this->exn->getFileName();
      AbstractFactory::
        getInstance('packages\\models\\db\\DbPersistenceFactory')
        ->getxPDO()->log( xPDO::LOG_LEVEL_DEBUG, 
        "[$class: $file] Поле [$name] не доступно.");
    }
    return $result;
  }
  /**
   * 
   * @param string $key
   * @param mixed $value
   */
  public function __set( $key, $value){
    $this->__data [$key]= $value;
  }
}