<?php
namespace packages\models\observer;

use packages\models\observer\Observer;

use packages\models\observer\Observable;

class NullObserver implements Observer {
	/**
	 * @param $subject Observable <p>
	 * The SplSubject notifying the observer of an update.
	 * </p>
	 * @return void 
	 */
	function update(Observable $subject) {
		
	}
	/**
	 * 
	 * Enter description here ...
	 */
	function __construct() {
		
	}
	/**
	 * 
	 * Enter description here ...
	 * @param $obs Observer 
	 * @return void
	 */
	function init(Observer $obs) {
		
	}
}