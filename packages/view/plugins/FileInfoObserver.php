<?php
namespace packages\view\plugins;
use packages\models\observer\Observable;

use packages\models\observer\Observer;

class FileInfoObserver implements Observer {
  /**
   * 
   * Enter description here ...
   * @var int
   */
  private $filename;
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $query;
	/**
	 * @param $subject Observable <p>
	 * The SplSubject notifying the observer of an update.
	 * </p>
	 * @return void 
	 */
	public function update (Observable $subject){
	  
	}
	/**
	 * 
	 * Enter description here ...
	 * @param $obs Observer
	 */
	function __construct(Observable $obs){
	  $this->filename = '';
	  $this->query = '';
	  foreach ( $obs->observers() as $class => $obr){
	    if ( $class == 'FileInfoObserver') {
	      $this->init($obr);
	      return ;
	    }
	  }
	  
	}
	/**
	 * 
	 * Enter description here ...
	 * @param $obs Observer 
	 * @return void
	 */
	public function init (Observer $obs){
	  $this->filename = $obs->getFileName();
	  $this->query = $obs->getQuery();
	}
	/**
	 * @return string
	 * Enter description here ...
	 */
	public function getFileName(){
	  return $this->filename;
	}
	/**
	 * 
	 * @param string $filename
	 */
	public function setFileName($filename){
	  $this->filename = $filename;
	}
	/**
	 * 
	 * @return string
	 */
	public function getQuery(){
	  return $this->query;
	}
	/**
	 * 
	 * @param string $query
	 */
	public function setQuery( $query){
	  $this->query = $query;
	}
}
