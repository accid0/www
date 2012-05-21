<?php
namespace packages\models\application;
use packages\view\expression\PluginExpression;

use packages\models\db\DbPersistenceFactory;

use packages\view\builder\NativeBuilder;

use packages\view\plugins\PluginObserver;

use packages\view\exception\PluginObserverException;

use packages\db\domain\UserDomain;

use packages\view\builder\Builder;
use packages\view\plugins\system\UserObserver;
use packages\models\factory\AbstractFactory;
use SimpleXMLElement,Exception,ErrorException;
use packages\models\application\Request;
use packages\models\application\ApplicationHelper;
use packages\view\expression\TemplateExpression;
use packages\models\application\Cookies;
use packages\models\application\Session;
use ArrayObject, xPDO;
class ApplicationController extends AbstractFactory {
  /**
   * 
   * Enter description here ...
   * @var float
   */
  static private $begin = 0;
  /**
   * 
   * Enter description here ...
   * @var float
   */
  static private $end = 0;
  private function profile(){
    if ( self::$begin === 0){
      self::$begin = microtime(TRUE);
      return ;
    }
    if ( self::$end !== 0) return ;
    self::$end = (float)(microtime(TRUE) - self::$begin);
    $cacheManager = $this->getDbPersistenceFactory()->getCacheManager();
    while( $cacheManager->lock('profile') === FALSE)
      usleep(1000);
    $profile = $cacheManager->get( 'profile/application');
    if ( !$profile){
      $profile = array( self::$end, 1 );
      $cacheManager->add('profile/application', $profile, array('profile') );
      $qu = self::$end;
      $count = 1;
    }
    else{
      $qu =  (float)($profile[0]);
      $qu = sqrt(( $qu * $qu + self::$end * self::$end)/2);
      $count = $profile[1] +1;
      $profile = array( $qu , $count);
      $cacheManager->set('profile/application', $profile, array('profile') );
    }
    $this->getDbPersistenceFactory()->getxPDO()->log( xPDO::LOG_LEVEL_DEBUG,
      "Произведено [$count] загрузок. Среднее время [$qu] секунд");
    $cacheManager->releaseLock('profile');
  }
  /**
   * 
   * Enter description here ...
   * @param bool $expr
   * @param string $msg
   * @throws Exception
   */
  private function ensure( $expr , $msg ){
    if ( $expr ) 
      throw new Exception($msg);
  }
  /**
   * 
   * Enter description here ...
   * @param int $errno
   * @param string $errstr
   * @param string $errfile
   * @param int $errline
   * @throws ErrorException
   */
  static function errorHandler( $errno, $errstr, $errfile, $errline){
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
  }
  /**
   * 
   * Enter description here ...
   * @param Exception $msg
   */
  static function errorPageNotFound(Exception $msg){
    try{
      ob_end_clean();
      ob_start();
      $appController = AbstractFactory::getInstance(
      	"packages\\models\\application\\ApplicationController");
      $pa = array( "!\{!xs", "!\}!xs", "!\s!xs");
      $re = array( "[", "]"," ");
      if ( DbPersistenceFactory::_DB_DEBUG){
        $appController->getPlugin('registry')->errorMsg = 
          preg_replace($pa, $re, $msg->getMessage() . " в файле[" . 
          $msg->getFile() . "] строка " . $msg->getLine());
      }else{
        $appController->getDbPersistenceFactory()->getxPDO()->log(
        xPDO::LOG_LEVEL_DEBUG, $msg);
        $appController->getPlugin('registry')->errorMsg = '404 не найдено' ;
      }
      $a_H = $appController->getPlugin( 'applicationHelper');
      //$errorPage = file_get_contents($a_H->templateFolder . 
      // $a_H->errorPage);
      $tpl = new PluginExpression($errorPage , 
              AbstractFactory::getInstance(
              'packages\\view\\factory\\FactoryStorage')->
              getObject('Null'));
      $tpl->setFileName((string)$a_H->application->errorPage);
      $builder = new NativeBuilder();
      $tpl -> visit($builder);
      header("HTTP/1.0 404 Not Found");
      header("Status: 404 Not Found");
      header("Content-Type: text/html;charset=" . 
        DbPersistenceFactory::_DB_CHARSET );
      print $builder->getDumpResult();
      $appController->getPlugin( 'session')->close();
      $appController->getDbPersistenceFactory()->closexPDO();
      ob_end_flush();
      return TRUE;
    }
    catch ( Exception $e){
      print $e->getMessage() . " in file[" . $e->getFile() . "] line " .
        $e->getLine();
    }
  }
  /**
   * (non-PHPdoc)
   * @see packages\models\factory.AbstractFactory::getObject()
   */
  function getObject($str){
    
  }
  /**
   * @return UserObserver
   * Enter description here ...
   */
  function getPlugin( $name){
    return AbstractFactory::getInstance(
  	  	'packages\\view\\factory\\FactoryPluginObserver') ->
        getObject( $name);
  }
  /**
   * @return DbPersistenceFactory
   * Enter description here ...
   */
  function getDbPersistenceFactory(){
    return AbstractFactory::getInstance(
      	"packages\\models\\db\\DbPersistenceFactory");
  }
  /**
   * 
   */
  function run(){
      //$this->profile();
      ob_start();
      //$this->getPlugin( 'session');
      @set_exception_handler(array(
        "packages\\models\\application\\ApplicationController" , 
        "errorPageNotFound"
      ));
      @set_error_handler(array(
        "packages\\models\\application\\ApplicationController" , 
        "errorHandler"),E_ALL & ~E_NOTICE & ~E_USER_NOTICE);
      $tpl = new PluginExpression('' , 
              AbstractFactory::getInstance(
              'packages\\view\\factory\\FactoryStorage')->getObject('Null'));
      $tpl = $this->getPlugin('extend')->execute( $tpl);
      $b = new NativeBuilder();
      $tpl -> visit($b);
      header("Content-Type: text/html;charset=" . 
        DbPersistenceFactory::_DB_CHARSET );
      //print $this->getDbPersistenceFactory()->getCacheManager()->getN();
      print $b->getDumpResult();
      $this->getPlugin( 'session')->close();
      //$this->profile();
      $this->getDbPersistenceFactory()->closexPDO();
      ob_end_flush();
  }
}