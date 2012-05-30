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
   * 
   * Enter description here ...
   * @var xPDO
   */
  private $xpdo = NULL;
  /**
   * 
   * Enter description here ...
   * @param string $msg
   */
  private function log( $msg){
    $this->xpdo->log( xPDO::LOG_LEVEL_DEBUG, $msg);
  }
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
  function __construct(PluginExpression $exn){
    $this->tpl = '';
    $this->__data = array();
    $this->exn = $exn;
    $this->aC =AbstractFactory::getInstance(
    	"packages\\models\\application\\ApplicationController");
    $this->baseFolder = (string)$this->aC->getPlugin('applicationHelper')->
      application->templateFolder;
    $this->xpdo = $this->aC->getDbPersistenceFactory()->getxPDO();
  }
  /**
   * 
   * Enter description here ...
   * @param TemplateExpression $exn
   */
  function visitPluginExpression( Template $exn){
    $this->exn = $exn;
    $this->tpl = '';
    $file = $exn->getFileName();
    $class = new ReflectionClass( $exn);
    $m = $exn->getQuery();
    //ob_start();
    if ( $class->hasMethod( $m)){
      $data = $exn->$m( $this);
    }
    if ( !empty($file)){
      try{
        include ( $this->baseFolder . $file);
      }
      catch ( Exception $e){
        //ob_end_clean();
        $this->log( "[NativeBuilder::" .  $file . "] " . $e->getMessage());
      }
      //$this->tpl = ob_get_clean();
    }
  }
  /**
   * 
   * Enter description here ...
   * @param string $name
   * @param array $arguments
   */
  public function __call($name, $arguments) {
    try{
      $result = NULL;
      if ( preg_match('@visit[a-zA-Z0-9]++@xs', $name)){
        $this->visitPluginExpression( $arguments[0]);
      }
      else{
        $plugin = $this->aC->getPlugin( $name);
        $tpl = $this->exn;
        $tpl->setDumpResult('name', $name);
        $arguments []= $tpl;
        $result = call_user_func_array( array( &$plugin, 'execute'), $arguments);
        if ( $result instanceof  Expression){
          $temp = $this->exn;
          if ( $result->getFileName() != $temp->getFileName()){
            $tpl->addStorage( $result);
            $result->visit( $this);
            print $this->getDumpResult();
          }
          elseif ( $result instanceof  HtmlExpression){
            print  $result;
            $result = '';
          }
          $this->exn = $temp;
        }
      }
    }
    catch ( Expression $e){
      $this->log("[NativeBuilder::$name] " . $e);
    }
    return $result;
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
      $this->log("[$class: $file] Поле [$name] не доступно.");
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