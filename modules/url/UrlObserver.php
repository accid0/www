<?php
namespace modules\url;
use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use packages\view\exception\PluginObserverException;
use packages\models\observer\Observer;
class UrlObserver extends PluginObserver {
    /**
    * (non-PHPdoc)
    * @see packages\view\plugins.PluginObserver::install()
    */
    protected function install(){
    }
	/**
	 * @param Expression $subject
	 */
	protected function doExecute(Expression $subject){
	  $query = '';
	  $params = $subject->getVars();
	  if ( !empty($params)){
    	  foreach ( $subject->getVars() as $key=>$vl){
    	   if ( strpos($key , "__equal$") !== FALSE  ){
    	        $query = $vl->getDumpResult('result');
    	    }
    	  }
	  }
	  $this->setResult( $this->getPlugin('applicationHelper')->application->baseurl . 
	  	"/index.php?q=$query");
    }
	/**
   	* (non-PHPdoc)
   	* @see packages\view\plugins.PluginObserver::init()
	*/
	function init(Observer $obs){
	}
	function __construct(){
	  parent::__construct();
	}
}