<?php
namespace packages\models\db;
use packages\models\factory\AbstractFactory;
use packages\models\application\ApplicationHelper;
use packages\models\application\Request;
use Exception, PDO, xPDO, xPDOCriteria, xPDOQuery, xPDOObject;
use xPDOCacheManager;
class DbPersistenceFactory extends AbstractFactory{
  const _DB_USER = '151619';
  const _DB_DNS = 'mysql:host=localhost;dbname=151619';
  const _DB_PASS = '12121212';
  const _DB_TYPE = 'mysql';
  const _DB_PREFIX = 'kpro_';
  const _DB_CHARSET = 'utf8';
  const _DB_DEBUG = FALSE;
  /**
   * 
   * Enter description here ...
   * @var xPDO
   */
  private $xPDO = NULL;
  /**
   * 
   * Enter description here ...
   * @var xPDOCacheManager
   */
  private $cacheManager = NULL;
  /**
   * 
   * Enter description here ...
   */
  private function dbGenerator(){
    $this->xPDO->getManager()->getGenerator()->
      writeSchema('packages/db/models/DomainDBModel.' . 
      self::_DB_TYPE . '.schema.xml',
      'Domain', 'xPDOObject', self::_DB_PREFIX);
  }
  /**
   * 
   * Enter description here ...
   */
  private function dbParser(){
    $this->xPDO->getManager()->getGenerator()->
      parseSchema('packages/db/models/DomainDBModel.' . 
      self::_DB_TYPE . '.schema.xml',
      'packages/db/');
  }
  /**
   * 
   * Enter description here ...
   * @param xPDOCriteria $query
   */
  private function log( xPDOCriteria $query){
    if ( self::_DB_DEBUG){
      $query->prepare();
      $this->getxPDO()->log(xPDO::LOG_LEVEL_DEBUG, $query->toSql());
    }
  }
  /**
   * @return xPDO
   * Enter description here ...
   */
  function getxPDO(){
  if ( is_null($this->xPDO) ){
     include_once 'packages/models/db/xpdo-2.1.3-pl/xpdo.class.php';
     $this->xPDO = new DB;
     if ( self::_DB_DEBUG){
  	   $level = xPDO::LOG_LEVEL_DEBUG;
  	   $this->xPDO->setDebug();
     }
	 else  $level = xPDO::LOG_LEVEL_DEBUG;
	 $this->xPDO->setLogLevel($level);
	 $this->xPDO->setLogTarget(array(
	 	'target' => 'FILE',
	 	'options' => array(
	 		'filename' => strftime('%Y.%m.%d') . '.log',
	 		'filepath' => 'logs/'
	     )
	 ));
     //if ( !file_exists('packages/db/models/DomainDBModel.' . 
     //  $applicationHelper->database->type . '.schema.xml')){
     //    $this->xPDO->setLogLevel(xPDO::LOG_LEVEL_INFO);
     //    $this->xPDO->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
     //    $this->dbGenerator();
     //    $this->dbParser();
     //}
     //$this->xPDO->setLogLevel(xPDO::LOG_LEVEL_INFO);
     //$this->xPDO->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
     //$this->dbParser();
     //$this->xPDO->addPackage( 'Domain' , 'packages/db/' , '');  
     //$query= new xPDOCriteria($this->xPDO, 
     //"SET NAMES " . self::_DB_CHARSET );
     //$query->prepare();
     //$query->stmt->execute();
    }
    return $this->xPDO;
  }
  /**
   * 
   */
  function closexPDO(){
    unset($this->xPDO);
    $this->xPDO = NULL;
  }
  /**
   * 
   * Enter description here ...
   * @param string $table
   * @return xPDOObject
   */
  public function newObject( $table ){
    return $this->getxPDO()->newObject( $table);
  }
  /**
   * 
   * Enter description here ...
   * @param string $table
   * @param string $graph
   * @param xPDOQuery $query
   * @return xPDOObject
   */
  public function getObjectGraph( $table , $graph, xPDOQuery $query){
    $query->bindGraph( $graph);
    return $this->getxPDO()->getObjectGraph( $table , $graph, $query);
  }
  /**
   * 
   * Enter description here ...
   * @param string $table
   * @param string $graph
   * @param xPDOQuery $query
   * @return array
   */
  public function getCollectionGraph( $table , $graph, xPDOQuery $query){
    $query->bindGraph( $graph);
    return new xPDOCollection( $this->getxPDO()->
      getCollectionGraph( $table , $graph, $query), $table, $graph);
  }
  /**
   * 
   * Enter description here ...
   * @param string $table
   * @param xPDOQuery $query
   * @return array
   */
  public function getCollection( $table , xPDOQuery $query){
    return new xPDOCollection( $this->getxPDO()->
      getCollection( $table , $query), $table);
  }
  /**
   * 
   * Enter description here ...
   * @param string $table
   * @param xPDOQuery $query
   * @return int
   */
  public function getCount( $table, xPDOQuery $query){
    return $this->getxPDO()->getCount( $table , $query);
  }
  /**
   * 
   * Enter description here ...
   * @param string $table
   * @param xPDOQuery $query
   * @return xPDOObject
   */
  public function getObject( $table , xPDOQuery $query){
    return $this->getxPDO()->getObject( $table , $query);
  }
  /**
   * 
   * Enter description here ...
   * @param string $table
   * @return xPDOQuery
   */
  public function newQuery( $table ){
    $query = $this->getxPDO()->newQuery($table);
    return $query;
  }
  /**
   * @return xPDOCacheManager
   * Enter description here ...
   */
  public function getCacheManager(){
    if ( is_null( $this->cacheManager)){
      $this->cacheManager = $this->getxPDO()->getCacheManager('cache.xPDOTagCacheManager');
    }
    return $this->cacheManager;
  }
}