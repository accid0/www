<?php
namespace packages\view\plugins;
use packages\models\application\Helper;

use packages\models\application\ApplicationHelperIterator;

use packages\view\plugins\system\CookiesObserver;

use packages\view\plugins\system\RequestObserver;

use packages\view\plugins\system\SessionObserver;

use packages\view\expression\TemplateExpression;
use packages\models\application\ApplicationHelper;
use packages\models\application\ApplicationController;
use packages\view\exception\PluginObserverException;

use packages\models\factory\AbstractFactory;

use packages\view\plugins\DumpObserver;
use packages\view\builder\ResultsVisitorer;

use packages\models\visitorer\FactoryVisitorer;

use packages\models\observer\Observable;

use packages\models\observer\Observer;

use packages\view\expression\Expression;
use packages\models\db\DbPersistenceFactory;
use packages\view\expression\HtmlExpression;
use xPDO, xPDOQuery, xPDOObject, ReflectionClass, SplFileInfo, 
  SimpleXMLElement, ArrayObject,xPDOCacheManager, Serializable;
abstract class  PluginObserver implements Observer , Serializable {
  /**
   * 
   * Enter description here ...
   * @var boolean
   */
  private static $error =FALSE;
  /**
   * 
   * Enter description here ...
   * @var boolean
   */
  private $system = FALSE;
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $name = NULL;
  /**
   * 
   * Enter description here ...
   * @var mixed
   */
  private $result = NULL;
  /**
   * File name of TemplateExpression class
   * @var string
   */
  private $fileName = NULL;
  /**
   * 
   * Enter description here ...
   * @var mixed
   */
  private $access = FALSE;
  /**
   * 
   * @var Helper
   */
  private $section = NULL;
  /**
   * 
   * Enter description here ...
   */
  private function loadPackage(Helper $section){
    if ( !is_null($section) && $section['access'] == 'enable'){
	    if ( $section['package'] != ''){
	      $this->getxPDO()->addPackage( $section['package'], 'packages/db/');  
	    }
	  }
  }
  /**
   * 
   * Enter description here ...
   * @param SimpleXMLElement $node
   */
  protected function access(ApplicationHelperIterator $node){
    $access = array();
    $aH = $this->getPlugin('applicationHelper');
    $authPlugin = (string)$aH->application->authPlugin;
    if ( $authPlugin == '') {
      $this->access = TRUE;
      return;
    }
    if ($this instanceof  AuthPlugin)  $authPlugin = $this;
    else  $authPlugin = $this->getPlugin($authPlugin);
    $role = $authPlugin->getRole();
    if ( isset( $node->permission))
      foreach ( $node->permission as $perm){
         $gr = array();
         foreach ( $perm->group as $group){
            $gr []= (string) $group;
         }
         $gr = array_intersect( $gr, $role);
         if ( !empty($gr)) $access []= $perm['action']; 
      }
    $this->access = $access;
  }
  /**
   * @param string $action
   * @param string $msg
   * @return boolean
   */
  protected function accessEnsure( $action, $msg = ''){
    if ( is_array( $this->access))
      return $this->ensure( !in_array( $action, $this->access), 
      	"[$this->name]:[$action]: Нет прав доступа." . $msg);
    else
      return $this->ensure( !$this->access, 
      	"[$this->name]:[$action]: Нет прав доступа." . $msg);
  }
  /**
   * 
   * Enter description here ...
   * @param mixed $value
   */
  protected function setResult( $value , $file = NULL){
    $this->result = $value;
    $this->fileName = $file;
  }
  /**
   * @return mixed
   * Enter description here ...
   */
  public function getResult(){
    return $this->result;
  }
  /**
   * @return ApplicationHelper
   * Enter description here ...
   */
  protected function getSection(){
    return $this->section;
  }
  /**
   * @return ApplicationController
   */
  protected function getApplicationController(){
    return AbstractFactory::getInstance(
    	"packages\\models\\application\\ApplicationController");
  }
  /**
   * @return PluginObserver
   */
  protected function getPlugin( $name){
    return $this->getApplicationController()->getPlugin( $name);
  }
  /**
   * @return DbPersistenceFactory
   */
  protected function getDbPersistenceFactory(){
    return $this->getApplicationController()->
      getDbPersistenceFactory();
  }
  /**
   * @return xPDO
   * @todo Koward сказал , что праймари кей всегда надо выбирать
   */
  protected function getxPDO(){
    return $this->getDbPersistenceFactory()->getxPDO();
  }
  /**
   * @return xPDOCacheManager
   * Enter description here ...
   */
  protected function getCacheManager(){
    return  $this->getDbPersistenceFactory()->getCacheManager();
  }
  /**
   * 
   * Enter description here ...
   * @param bool $expr
   * @param string $msg
   * @return boolean
   * @throws PluginObserverException
   */
  protected function ensure( $expr , $msg ){
    if ( self::$error) return TRUE;
    if ( $expr) {
      self::$error = TRUE;
      throw new PluginObserverException("[$this->name]" . $msg );
    }
    return FALSE;
  }
  /**
   * 
   * Enter description here ...
   * @param string $msg
   */
  protected function log($msg){
    $this->getxPDO()->log( xPDO::LOG_LEVEL_DEBUG, $msg);
  }
  /**
   * 
   * Enter description here ...
   * @param string $str
   * @return HtmlExpression
   */
  protected function toHtml( $str){
    return new HtmlExpression( $str, AbstractFactory::getInstance(
    	'packages\\view\\factory\\FactoryStorage')->getObject('Null'));
  }
  /**
   * @todo serialized data
   */
  public function serialize(){
    $data = array(
    'name' => $this->getName(),
    'section' => $this->getSection(),
    'system' => $this->system
    );
    return serialize($data);
  }
  /**
   * @todo deserialized data
   * @param mixed $data
   */
  public function unserialize( $data){
    $data = unserialize( $data);
    $this->name = $data['name'];
    $this->section = $data['section'];
    $this->system = $data['system'];
    PluginObserver::loadPackage();
	if ( !$this->system && !( $this instanceof  AuthPlugin) ){
      $this->access($this->getSection()->permissions);
	}
    $this->init($this);
  }
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
	  $class = new ReflectionClass($obs);
	  $classname = $class->getShortName();
	  $path = str_replace( "\\", "/", $class->getNamespaceName());
	  $this->name = str_replace( "Observer", "", $classname);
	  if ( $this->name === 'ApplicationHelper'){
	    $aH = new ApplicationHelper();
	    $this->setResult( $aH);
	  }
	  else
    	$aH = $this->getPlugin('applicationHelper');
	  if ( $path === 'packages/view/plugins/system'){
	    $this->system =TRUE;
	    $name = $this->name;
	  }
	  else {
	    $name = str_replace( (string)$aH->application->pluginFolder, '', $path);
	    $name = str_replace('/', ' ', $name);
	    $name = ucwords($name);
	    $name = str_replace(' ', '', $name);
	    $this->name = $name;
	  }
	  $key = $this->getCacheManager()->get("plugins/$name");
	  if ( !$key){
	    if ( $this->system)
	      $section = $aH->plugins->system->find( 'plugin', 'name', $name);
	    else
	      $section = $aH->plugins->modules->find( 'plugin', 'name', $name);
	    if ( $section !== NULL)
	      $section = $section->replecate( "plugins/$name");
	  }
	  else{
	    $section = new ApplicationHelper;
	    $section->reset( $key);
	  }
	  if (  is_null($section) ){
	    if ( $this->system)
	      $section = $aH->plugins->system;
	    else 
	      $section = $aH->plugins->modules;
	    $section = $section->addChild('plugin');
	    $section ['name']= $name;
	    $section ['access']=  'enable';
	    $section ['cache']= 'false';
	    $section->addChild('options');
	    $section->addChild('permissions');
	    $section->addChild('lang');
	    $file = new SplFileInfo( $path . "/" . 
	      $this->name . ".inc.xml");
	    if ( $file->isFile()){
	      $this->getxPDO()->getManager()->getGenerator()->
            parseSchema( $file->getPathname(),  'packages/db/'); 
	      $xml = simplexml_load_file( $file->getPathname());
          if ($this->ensure( !$xml, "Не валидный файл xml")) return;
          $package = (string)$xml['package'];
          $xsection = $xml->xpath("/model/permissions");
          if ( !empty($xsection)){
            $section->permissions->mergeXml( $xsection[0]);
          }
          $xsection = $xml->xpath("/model/options");
          if ( !empty($xsection)){
            $section->options->mergeXml( $xsection[0]);
          }
          $xsection = $xml->xpath("/model/request");
          if ( !empty($xsection)){
            $aH->control->request->mergeXml( $xsection[0]);
          }
          $xsection = $xml->xpath("/model/lang");
          if ( !empty($xsection)){
            $section->lang->mergeXml( $xsection[0]);
          }
          //$xml->asXml( $file->getPathname());
	      if ( $package != ''){
	        $section ['package']= $package; 
	      }
	    }
	    $this->loadPackage( $section);
	    $this->install();
	  }
	  else  $this->loadPackage( $section);
	  $section->setXPath('//plugin[normalize-space(@name)="' .  $name . '"]');
	  $this->section = $section;
	}
	/**
	 * @return string
	 * Enter description here ...
	 */
	public function getName(){
	  return $this->name;
	}
	/**
	 * @todo Если разрешить системным плагинам авторизоваться, то
	 * получается циклическое замыкание в работе фабрики плагинов
	 * потому что даже для создания плагина User должен быть создан
	 * плагин Registry , а если его авторизовывать то получается цикл
	 * User=>Registry
	 * @todo второй момент связан с тем, что если авторизовывать сам
	 * плагин авторизации, то происходит попытка авторизации до 
	 * момента инициализации плагина и функцию авторизации не 
	 * передается данных о пользователе
	 */
  function __construct(){
	PluginObserver::init( $this);
	if ( !$this->system && !( $this instanceof  AuthPlugin) ){
      $this->access($this->getSection()->permissions);
    }
	$this->init($this);
  }
	/**
	 * Метод вызывается акцептором и выполняет действия плагина,
	 * которые в конечном итоге приводят к сохранению в кеше или
	 * в дочернем узле акцептора
	 * @return void
	 * @see Storage::__call()
	 * @see DumpObserver::setDumpResult()
	 * @see CompositeStorage::addStorage()
	 * @param array $arguments
	 */
	function execute( ){
	  $args = func_get_args();
	  call_user_func_array( array( &$this, 'doExecute'), $args);
	  if ( is_null($this->getResult())) return ;
	  if ( is_string($this->getResult())){
	    $newTempl = new TemplateExpression(
	      $this->getResult(), end($args));
	    if ( !is_null($this->fileName)) $newTempl->
	      setFileName($this->fileName);
	    return $newTempl;
	  } 
	  else 
	    return $this->getResult();
	}
	/**
	 * 
	 */
	protected abstract function install();
	
}