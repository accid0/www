<?php
namespace modules\lang;
use packages\models\application\Helper;

use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use packages\view\exception\PluginObserverException;
use packages\models\observer\Observer;
class LangObserver extends PluginObserver {
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $locale = '';
  /**
   * 
   * Enter description here ...
   * @var array
   */
  private $lang = array();
  /**
   * 
   * Enter description here ...
   * @param array $keys
   */
  private function getWords( $keys = array()){
    $result = array();
    if ( empty($keys)){
      foreach ($this->lang as $id => $locs)
        $result [$id]= $locs[$this->locale];
    }
    else 
      foreach ( $keys as $id => $locale)
        $result [$id]= $this->lang[$id][$locale];
    return $result;
  }
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
	  $keys = array();
	  foreach ( $subject->getVars() as $key=>$vl){
    	 if ( strpos($key , "__equal$") !== FALSE ){
    	   $l = $vl->getL_Var()->expression();
    	   if ( is_numeric( $l))
    	      $keys [$vl->getDumpResult('result')]= $this->locale;
    	   else
    	     $keys [$vl->getL_Var()->expression()]= $vl->getDumpResult('result');
    	 }
      }
      $this->setResult( $this->getWords( $keys));
    }
	/**
   	* (non-PHPdoc)
   	* @see packages\view\plugins.PluginObserver::init()
	*/
	function init(Observer $obs){
	  $request = $this->getPlugin('request');
	  $cookies = $this->getPlugin( 'cookies');
	  if ( isset( $request->locale)){
	    $this->locale = $request->locale;
	    $cookies->locale = $this->locale;
	  }
	  elseif ( !isset( $cookies->locale)){
	    foreach ( $this->getSection()->options->option as $op){
	      if ( $op['name'] === 'defaultLocale'){
	        $cookies->locale = $op['value'];
	        $this->locale = $op['value'];
	      }
	    }
	  }
	  else  $this->locale = $cookies->locale;
	  $cache = $this->getCacheManager();
	  $this->lang = $cache->get( 'lang/init');
	  if ( !$this->lang){
	    $ar = array();
    	$this->lang = $this->getPlugin('applicationHelper')->findTree( 'lang')->
    	  findTree('expression');
    	foreach ( $this->lang as $l)
    	  if ( $l['id'] != ''){
    	    $loc = array();
    	    foreach ( $l->locale as $locale){
    	      $loc [$locale['name']]= $locale['value'];
    	    }
    	    $ar [$l['id']]= $loc;
    	  }
    	$cache->add( 'lang/init', $ar, array( Helper::APPLICATION_COMMON_TAG));
    	$this->lang = $ar;
	  }
	  $this->setResult($this->getWords());
	}
	/**
	 * 
	 * Enter description here ...
	 */
	function __construct(){
	  parent::__construct();
	}
}