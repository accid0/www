<?php
namespace packages\view\factory;

use packages\view\exception\FactoryPluginObserverException;

use packages\models\factory\AbstractFactory;

use ArrayObject,ReflectionClass,SplFileInfo,Exception;

use packages\view\plugins\PluginObserver;

use xPDO, xPDOCacheManager;

class FactoryPluginObserver extends AbstractFactory{
  /**
   * 
   * Enter description here ...
   * @var string
   */
  const _SYSTEM_PATH = 'packages\\view\\plugins\\system\\';
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $path = '';
  /**
   * 
   * @return string
   */
  private function getPath(){
    if ( $this->path === ''){
      $this->path = (string)$this->getPlugin( self::_SYSTEM_PATH . 
      	'ApplicationHelperObserver', 'applicationHelper')->application
        ->pluginFolder;
    }
    return $this->path;
  }
  /**
   * 
   * @todo здесь выбирать нужно ли серилизовать в кеш плагины или нет
   * закомментировать если не надо первую строку
   * @param string $class
   * @return PluginObserver
   */
  private function getPlugin( $class, $key){
    //if ( parent::isObject($class))
      return parent::getObject( $class);
    $cm = parent::getInstance('packages\\models\\db\\DbPersistenceFactory')
      ->getCacheManager();
    $plugin = $cm->get("factory/$key");
    if ( !$plugin){
      $plugin = parent::getObject( $class);
      $plugin = serialize( $plugin);
      $cm->add( "factory/$key", $plugin, array('factory'));
    }
    $plugin = unserialize( $plugin);
    parent::registerObject($class, $plugin);
    return $plugin;
  }
  /**
   * (non-PHPdoc)
   * @see packages\models\factory.AbstractFactory::getObject()
   * 
   */
  public function getObject ($str){
    if ( empty($str))  throw new FactoryPluginObserverException(
      	"[FactoryPluginObserver]: Плагин не может быть пустой строкой [$str]");
    if ($str == 'Null')
      $str = 'packages\\models\\observer\\NullObserver';
    if ( $str === 'applicationHelper')
      return $this->getPlugin(self::_SYSTEM_PATH . 
      	'ApplicationHelperObserver',  'applicationHelper');
    $registry = $this->getPlugin( self::_SYSTEM_PATH . 
    	'RegistryObserver', 'registry');
    if ( $str === 'registry' || isset( $registry->$str))
      return $registry;
    try{
      $pathp = $this->getPath();
      $file_modules = new SplFileInfo($pathp . $str."Observer.php");
      $name_module = $file_modules->getBaseName("Observer.php");
      $pathp = str_replace('\\', DIRECTORY_SEPARATOR , $pathp);
      $pathp = $file_modules->getPath() . DIRECTORY_SEPARATOR . 
        $name_module;
      $file_modules = new SplFileInfo(__ROOT_DIR__ . 
        DIRECTORY_SEPARATOR . $pathp . DIRECTORY_SEPARATOR .
        ucfirst($name_module)."Observer.php");
      if ($file_modules->isFile() === TRUE){
        
        $class = str_replace('/','\\',$pathp) . '\\' . $file_modules->getBaseName(".php");
        return $this->getPlugin( $class, $str);
      }
      $file_system = new SplFileInfo( __ROOT_DIR__ . 
        DIRECTORY_SEPARATOR . str_replace('\\',  DIRECTORY_SEPARATOR, 
        self::_SYSTEM_PATH) . ucfirst( $str) ."Observer.php");
      if ($file_system->isFile() === TRUE){
        
        $class = self::_SYSTEM_PATH . $file_system->getBaseName(".php");
        return $this->getPlugin( $class, $str);
      }
    }
    catch (Exception $e){
      throw new FactoryPluginObserverException(
      	"[FactoryPluginObserver::$str] " . $e);
    }
    if ( !is_dir($file_modules->getPath())){
      @mkdir($file_modules->getPath(), 0777, true);
      if ( !is_writable($file_modules->getPath())) {
        @ chmod($file_modules->getPath(), 0777);
      }
    }
    $fs = $file_modules->openFile('a');
    $nas = str_replace('/','\\',$pathp);
    $cls =  $file_modules->getBasename('.php');
    $code = <<<EOF
<?php
/**
*@name $nas\\$cls.php
*@packages models
*@subpackage plugin
*@author Andrew Scherbakov
*@version 1.0
*@copyright created
*/
namespace $nas;
use packages\\view\\expression\\Expression;
use packages\\view\\plugins\\PluginObserver;
use packages\\models\\observer\\Observer;
class $cls extends PluginObserver {
  /**
  * (non-PHPdoc)
  * @see packages\view\plugins.PluginObserver::install()
  */
  protected function install(){
  }
  /**
  * @param Expression \$subject
  */
  protected function doExecute(Expression \$subject){
  }
  /**
  * (non-PHPdoc)
  * @see packages\view\plugins.PluginObserver::init()
  */
  function init(Observer \$obs){
  }
  /**
  *@todo // constructor plugin
  */
  function __construct(){
  	parent::__construct();
  }
}
EOF;
    $fs->fwrite($code);
    unset($fs);
    return $this->getPlugin( $nas . '\\' . $cls, $str);
  }
}