<?php
namespace packages\models\observer;

class IdObserver implements Observer {
  /**
   * 
   * Enter description here ...
   * @var int
   */
  private $id;
  /**
   * 
   * Enter description here ...
   * @var int
   */
  private static $all = 0;
	/**
	 * @param subject Observable <p>
	 * The SplSubject notifying the observer of an update.
	 * </p>
	 * @return void 
	 */
	public function update (Observable $subject){
	  
	}
	/**
	 * 
	 * Enter description here ...
	 */
	function __construct(){
	  self::$all++;
	  $this->id = self::$all;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param Observer $obs
	 * @return void
	 */
	public function init (Observer $obs){
	  
	}
	/**
	 * @return int
	 * Enter description here ...
	 */
	public function getId(){
	  return $this->id;
	}
}