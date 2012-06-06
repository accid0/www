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
	 * @param string $query
	 * @param Expression $subject
	 */
	protected function doExecute( $query, Expression $subject){
	  $this->setResult( $this->getPlugin('helper')->application->baseurl . 
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