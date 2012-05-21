<?php
namespace packages\models\storage;

use packages\models\observer\Observable;
use ArrayObject as ArrayObject;

abstract class CompositeStorage extends Storage {
	/**
	 * 
	 * Enter description here ...
	 * @var ArrayObject $storages
	 */
	private $storages;
	/**
	 * 
	 * Enter description here ...
	 * @param Observable $observers
	 * @return void
	 */
	function __construct(Observable $obl){
		parent::__construct($obl);
		$this->storages = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
	}
	/**
	 * @return ArrayObject $storages
	 * Enter description here ...
	 */
	public function storages () {
		return $this->storages;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param Storage $storage
	 * @return void
	 */
	function removeStorage (Storage $storage) {
		$storages = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
		foreach($this->storages as $st) {
			if ($st !== $storage) {
				$storages[] = $st;
			}
		}
		$this->storages = $storages;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param Storage
	 * @return void
	 */
	function addStorage (Storage $storage) {
		foreach($this->storages as $stg) {
			if ($stg === $storage) return;
		}
		$this->storages[] = $storage;
	}
}