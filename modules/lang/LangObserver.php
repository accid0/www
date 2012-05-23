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
   * @param xPDOCacheManager $cache
   * @param string $locale
   */
  private function getLocale( $locale){
    $cache = $this->getCacheManager();
	$lang = $cache->get( "lang/$locale");
	if ( !$lang){
	  $ar = array();
      $lang = $this->getPlugin('applicationHelper')->findTree( 'lang')->
        findTree('expression');
      foreach ( $lang as $l)
        if ( $l['id'] != ''){
          $loc = $l->find( 'locale', 'name', $locale);
      	  if ( !is_null( $loc))  $ar [$l['id']]= $loc['value'];
        }
      $cache->add( "lang/$locale", $ar, array( Helper::APPLICATION_COMMON_TAG));
      $lang = $ar;
	}
    return $lang;
  }
  /**
   * 
   * Enter description here ...
   * @param array $keys
   */
  private function getWords( $keys = array()){
    $result = array();
    if ( empty($keys)){
      $result = $this->lang[$this->locale];
    }
    else 
      foreach ( $keys as $id => $locale){
        if ( !isset( $this->lang[$locale]))
          $this->lang [$locale]= $this->getLocale($locale);
        $result [$id]= $this->lang[$locale][$id];
      }
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
	 * @param mixed $keys
	 */
	protected function doExecute($keys, Expression $subject){
	  if ( is_null( $keys))  $keys = array();
	  if ( !is_array( $keys))  $keys = array( $keys);
	  $result = array();
	  foreach ( $keys as $key=>$vl){
	    if ( is_numeric( $key))
    	  $result [$vl]= $this->locale;
    	else
    	  $result [$key]= $vl;
      }
      $this->setResult( $this->getWords( $result));
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
	  $this->lang [$this->locale]= $this->getLocale( $this->locale);
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