<?php
namespace packages\models\observer;
use packages\models\observer\Observer as Observer;
use packages\models\storage\Storage as Storage;

interface Observable {
	/**
	 * 
	 * Enter description here ...
	 * @param Observer $observer
	 * @return void
	 */
	function attach(Observer $observer);
	/**
	 * 
	 * Enter description here ...
	 * @param Observer $observer
	 * @return void
	 */
	function detach(Observer $observer);
	/**
	 * 
	 * Enter description here ...
	 * @return void
	 */
	function notify ();

	/**
	 * @return ArrayObject
	 * Enter description here ...
	 */
	public function observers();
	/**
	 * 
	 * Enter description here ...
	 * @param Observable $obl
	 * @return void
	 */
	function init(Observable $obl);
}