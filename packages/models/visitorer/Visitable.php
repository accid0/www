<?php
namespace packages\models\visitorer;
use packages\models\visitorer\Visitorer as Visitorer;

interface Visitable {
	/**
	 * 
	 * Enter description here ...
	 * @param Visitorer $vsr
	 * @return void
	 */
	function visit(Visitorer $vsr);
}