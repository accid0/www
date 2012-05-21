<?php
namespace packages\view\expression;
use packages\view\plugins\FileInfoObserver;

use packages\models\factory\AbstractFactory;

use packages\models\observer\IdObserver;

use packages\models\observer\FactoryObserver;

use packages\models\observer\DepthObserver;

use packages\models\storage\FactoryStorage;
use packages\models\storage\CompositeStorage;
use packages\models\observer\Observable;

class Expression extends CompositeStorage {
	/**
	 * 
	 * Enter description here ...
	 * @var string
	 */
	private $exrn;
	/**
	 * @return string
	 * Enter description here ...
	 */
	function expression() {
		return $this->exrn;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param string $str
	 * @param Observable $obl
	 */
	function __construct($str = '',Observable $obl) {
		parent::__construct(AbstractFactory::getInstance('packages\view\factory\FactoryStorage')->getObject('Null'));
		$this->exrn = $str;
		$this->attach(new DepthObserver($obl))
		  ->attach(new IdObserver())
		  ->attach(new FileInfoObserver($obl));
	}
}