<?php
namespace packages\models\db;
use packages\models\factory\AbstractFactory;
use xPDO, PDO;
class DB extends xPDO{
  function __construct(){
    parent::__construct(  DbPersistenceFactory::_DB_DNS,  
        DbPersistenceFactory::_DB_USER,  DbPersistenceFactory::_DB_PASS, 
        array(
        xPDO::OPT_CACHE_PATH => 'cache/',
        xPDO::OPT_CACHE_HANDLER => 'cache.xPDOTagFileCache',
        //xPDO::OPT_CACHE_DB_HANDLER => 'cache.xPDOFileCache',
        //xPDO::OPT_CACHE_DB_OBJECTS_BY_PK => TRUE,
        //xPDO::OPT_CACHE_DB_COLLECTIONS => TRUE,
        xPDO::OPT_CACHE_FORMAT => 0,
        //xPDO::OPT_CACHE_DB => TRUE,
  	    xPDO::OPT_TABLE_PREFIX => DbPersistenceFactory::_DB_PREFIX,
        xPDO::OPT_HYDRATE_FIELDS => TRUE,
  	    xPDO::OPT_HYDRATE_RELATED_OBJECTS => TRUE,
  	    xPDO::OPT_HYDRATE_ADHOC_FIELDS => TRUE,
  	    xPDO::OPT_VALIDATE_ON_SAVE => TRUE,
  	    xPDO::OPT_AUTO_CREATE_TABLES => TRUE,
  	    'charset' => DbPersistenceFactory::_DB_CHARSET
      ), array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_PERSISTENT => FALSE,
		PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE
      )
    );
  }
}
