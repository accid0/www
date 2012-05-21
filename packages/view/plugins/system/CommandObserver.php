<?php
namespace packages\view\plugins\system;

use packages\models\observer\Observer;

use packages\view\expression\TemplateExpression;
use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use ArrayObject;

class CommandObserver extends PluginObserver {
  /**
   * 
   * Enter description here ...
   * @var array
   */
  private $stackCommand = array();
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::install()
   */
  protected function install(){
    
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::init()
   */
  function init(Observer $obs){
  }
	/**
	 * @return string
	 * @param Expression $subject
	 */
	protected function doExecute(Expression $subject){
	  $view = 'default';
	  $parent = $subject->getFileName();
	  $params = array();
	  $params = $subject->getVars();
	  if ( !empty($params)){
    	  foreach ( $subject->getVars() as $key=>$vl){
    	   if ( strpos($key , "__equal$") !== FALSE && 
    	      $vl->getL_Var()->expression() == 'view' ){
    	        $view = $vl->getDumpResult('result');
    	    }
    	  }
	  }
	  if ( $parent !== ''){
	    $this->ensure( !isset($this->stackCommand[$parent]), 
        	"Имя родительского шаблона [$parent] указано неверно");
	    $lastCommand = $this->stackCommand[$parent]['object'];
	    $comName = $this->stackCommand[$parent]['com'];
	  }
	  else{
	    $lastCommand = $this->getPlugin('applicationHelper')->control;
	    $comName = $this->getPlugin( 'request')->getCommand();
	  }
      $this->ensure(!isset($lastCommand->request) , 
        "Файл конфигурации не содержит следующего запроса");
      $this->ensure(is_null($comName) ,
        "Не найдена комманда запроса");
      foreach( $lastCommand->request->command as $command){
        if ( $command["name"] == $comName ){
          $path_template_file = '';
          foreach ($command->view as $v){
            if ($v["name"] == $view)
              $path_template_file = (string) $v->file;
          }
          $this->ensure( $path_template_file === '',
            "Не найдено представление для комманды $comName");
          $this->stackCommand [$path_template_file]['object'] = $command;
          $this->stackCommand [$path_template_file]['com'] = 
            $this->getPlugin( 'request')->getCommand();
          $path_folder = $this->getPlugin( 'applicationHelper')->templateFolder;
          $this->ensure(!file_exists($path_folder . $path_template_file),
            "Не существует файл представления $path_template_file");
          $text = file_get_contents($path_folder . $path_template_file);
  	      $this->setResult($text ,  $path_template_file);
  	      return ;
        }
      }
      $this->ensure(TRUE, "В файле конфигурации не найдено
      	определение комманды [$comName]");
	}

	/**
	 * @return void
	 */
	function __construct(){
	  parent::__construct();
	}
}