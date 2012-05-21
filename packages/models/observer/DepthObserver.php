<?php
namespace packages\models\observer;

class DepthObserver implements Observer {
  /**
   * 
   * Enter description here ...
   * @var int
   */
  private $depth = 0;
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
	  foreach ( $obs->observers() as $class => $obr){
	    if ( $class == 'DepthObserver') {
	      $this->init($obr);
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
	  $this->depth += $obs->getDepth() + 1;
	}
	/**
	 * @return int
	 * Enter description here ...
	 */
	public function getDepth(){
	  return $this->depth;
	}
}
