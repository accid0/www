<?php
namespace packages\view\plugins\system;

use packages\view\expression\Template;

use packages\models\observer\Observer;

use packages\view\expression\TemplateExpression;
use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use ArrayObject, ReflectionClass,SplFileInfo;

class ExtendObserver extends PluginObserver {
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $viewsFolder = '';
  /**
   * 
   * @var array
   */
  private $commands = array();
  /**
   * 
   * @var int
   */
  private $total = 0;
  /**
   * 
   * Enter description here ...
   * @var array
   */
  private $stackCommand = array();
  /**
   * @param int
   * @return string
   */
  private function getCommand( $current){
    if ($current < $this->total){
      return $this->commands[$current];
    }
    else 
      return NULL;
  }
  /**
   * 
   * Enter description here ...
   * @param string $class
   * @param TemplateExpression $parent
   * @return TemplateExpression
   */
  private function createTemplate( $classname, $parent){
    try{
      $file = new SplFileInfo( __ROOT_DIR__. DIRECTORY_SEPARATOR . 
        str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php');
      if ( $file->isFile() === TRUE){
        $result = new $classname( '', $parent);
      }
      else{
        $namesp = str_replace('/', '\\', $file->getPath());
        $name = $file->getBasename('.php');
        $class = new ReflectionClass( $parent);
        $parentClass = $class->getShortName();
        $use = $class->getName();
        $date = date(" Y - m M - d D");
        $code =<<<EOF
<?php
/**
*@name $namesp\\$name.php
*@packages models
*@subpackage application
*@author Andrew Scherbakov
*@version 1.0
*@copyright created $date
*/
namespace $namesp;
use $use;
use packages\models\visitorer\Visitorer;
class $name extends $parentClass{
  /**
  *@todo Укажите файлы шаблонов относительно параметров запроса
  *@var array
  */
  protected \$templates = array();
  /**
   * (non-PHPdoc)
   * @see packages\view\expression.PluginExpression::initialize()
   */
  protected function initialize( Visitorer \$controller){
  /**
   *@todo здесь общая инициализация
   */
  }
}
EOF;
        if ( !is_dir($file->getPath()))
          @mkdir($file->getPath(), 0777, true);
        $fs = $file->openFile('a');
        $fs->fwrite( $code);
        $fs->eof();
        $result = new $classname( '', $parent);
      }
    }
    catch ( Exception $e){
      $this->ensure( TRUE, "Класса [$classname] не существует");
    }
    $this->ensure( !($result instanceof  Template), 
    	"Класс шаблона [$classname] должен имплементировать интерфейс [Template]");
    return $result;
  }
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
    $req = $this->getPlugin('request');
    $this->commands = explode("/" , $req->q);
    $this->total = count( $this->commands);
    $ah = $this->getPlugin('applicationHelper')->application;
    $this->viewsFolder = $ah->viewsFolder;
  }
	/**
	 * @return string
	 * @param string $view
	 * @param Expression $subject
	 */
	protected function doExecute($view, Expression $subject){
	  $parent = $subject->getFileName();
	  $class = new ReflectionClass($subject);
	  $namespc = $class->getNamespaceName();
	  $classnm = $class->getShortName();
	  //$this->log("class $classnm");
	  if ( $parent !== ''){
	    $this->ensure( !isset($this->stackCommand[$parent]), 
        	"Имя родительского шаблона [$parent] указано неверно");
	    $lastIndex = $this->stackCommand[$parent];
	  }
	  else
	    $lastIndex = -1;
	  ++$lastIndex;
	  $lastCommand = strtolower($this->getCommand( $lastIndex));
	  if ( $parent !== ''){
	    $precommand = strtolower($this->getCommand( $lastIndex - 1));
	    $namespc .= '\\' . $precommand . '\\'; 
	    $this->ensure( empty($view), 
        	"Пропущено имя наследуемого шаблона от [$parent]");
	    $classnm = str_replace( 'Template', '', $classnm);
	    $classnm = $classnm . ucfirst( strtolower( $view)) . 'Template';
	  }
	  else {
	    $base = $this->viewsFolder;
	    $namespc = str_replace( '/', '\\', $base);
	    $classnm = 'MainTemplate';
	  }
	  $templ = $this->createTemplate( $namespc . $classnm, $subject);
	  $file = $templ->prepareFileTemplate( $lastCommand);
	  if ( !empty($file))
	    $this->stackCommand[$file] = $lastIndex;
	  $this->setResult( $templ);
	}
	/**
	 * @return void
	 */
	function __construct(){
	  parent::__construct();
	}
}