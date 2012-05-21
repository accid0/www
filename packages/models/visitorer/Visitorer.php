<?php
namespace packages\models\visitorer;
use packages\models\storage\CompositeStorage as CompositeStorage;
use packages\models\storage\Storage as Storage;

abstract class Visitorer {
  /**
   * 
   * Enter description here ...
   * @param Storage $stg
   * @return void
   */
	function visitStorage(Storage $stg) {
		$stg->notify();
	}
	/**
	 * 
	 * Enter description here ...
	 * @param CompositeStorage $cmtstg
	 * @return void
	 */
	function visitCompositeStorage(CompositeStorage $cmtstg) {
		$stges = $cmtstg->storages();
		$cmtstg->notify();
		foreach($stges as $stg)
			$stg->visit($this);
	}
}