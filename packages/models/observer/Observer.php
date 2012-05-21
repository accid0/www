<?php
namespace packages\models\observer;
use packages\models\observer\Observable as Observable;

interface Observer {
	/**
	 * @param $subject Observable <p>
	 * The SplSubject notifying the observer of an update.
	 * </p>
	 * @return void 
	 */
	public function update (Observable $subject);
	/**
	 * 
	 * Enter description here ...
	 * @param $obs Observer 
	 * @return void
	 */
	public function init (Observer $obs);
}
