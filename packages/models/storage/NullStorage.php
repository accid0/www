<?php
namespace packages\models\storage;
use packages\models\visitorer\Visitorer;

use packages\models\visitorer\Visitable;

use packages\models\observer\Observable as Observable;
use packages\models\observer\Observer as Observer;
use ArrayObject as ArrayObject;
class NullStorage implements Observable , Visitable {
	private $nullArray;
	function __construct() {
		$this->nullArray = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
	}
	/**
	 * 
	 * Enter description here ...
	 * @param Observer $observer
	 * @return void
	 */
	function attach(Observer $observer){
		
	}
	/**
	 * 
	 * Enter description here ...
	 * @param Observer $observer
	 * @return void
	 */
	function detach(Observer $observer){
		
	}
	/**
	 * 
	 * Enter description here ...
	 * @return void
	 */
	function notify (){
		
	}

	/**
	 * @return ArrayObject
	 * Enter description here ...
	 */
	public function observers(){
		return $this->nullArray;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param Observable $obl
	 * @return void
	 */
	function init(Observable $obl){
		
	}
	/**
	 * 
	 * Enter description here ...
	 * @param Visitorer $vsr
	 * @return void
	 */
	function visit(Visitorer $vsr){
	  
	}
}
