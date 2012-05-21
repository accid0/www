<?php
namespace packages\models\storage;
use packages\models\storage\Storage;
use packages\models\observer\Observable;
use packages\models\observer\Observer;
use ArrayObject;
use ReflectionClass;
use ReflectionMethod;
use packages\models\visitorer\Visitable;
use packages\models\visitorer\Visitorer;
use packages\models\exception\StorageInitException;
use packages\models\exception\StorageVisitException;
use packages\models\exception\StorageAttachException;
use packages\models\exception\StorageDetachException;

abstract class Storage implements Observable, Visitable {
	/**
	 * 
	 * @var ArrayObject
	 */
	private $observers;
	/**
	 * 
	 * Enter description here ...
	 * @param Observable $observers
	 * @return void
	 */
	function __construct(Observable $obl){
		$this->observers = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
		$this->init($obl);
		$this->notify();
	}
	/**
	 * 
	 * Enter description here ...
	 * @return void
	 * @param Observable $obl
	 */
	function init(Observable $obl) {
		$observers = $obl->observers();
		foreach($observers as $obr) {
		  try {
		    $class = new ReflectionClass($obr);
		  }
		  catch (Exception $e){
		    throw new StorageInitException("Невозможно получить класс объекта"); 
		  }
		  if( !is_null($this->observers[$class->getShortName()]) ) {
				$this->observers[$class->getShortName()] = new $class($obr);
		  }
		}
	}
	/**
	 * Attach an Observer
	 * @link http://www.php.net/manual/en/splsubject.attach.php
	 * @param observer Observer <p>
	 * The Observer to attach.
	 * </p>
	 * @return Storage 
	 */
	function attach(Observer $observer) {
	  try {
		  $class = new ReflectionClass($observer);
	  }
	  catch (Exception $e){
		  throw new StorageAttachException("Невозможно получить класс объекта"); 
	  }
	  $this->observers[$class->getShortName()] = $observer;
	  return $this;
	}

	/**
	 * Detach an Observer
	 * @link http://www.php.net/manual/en/splsubject.detach.php
	 * @param observer Observer <p>
	 * The Observer to detach.
	 * </p>
	 * @return Storage 
	 */
	function detach (Observer $observer) {
		$observers = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
		try {
		  $class1 = new ReflectionClass($observer);
		}
		catch (Exception $e){
		  throw new StorageDetachException("Невозможно получить класс объекта"); 
		}
		foreach ($this->observers as $obs) {
		    try{
			  $class2 = new ReflectionClass($obs);
		    }
		    catch (Exception $e){
		      throw new StorageDetachException("Невозможно получить класс объекта"); 
		    }
			if ($class1->getShortName() !== $class2->getShortName()) {
				$observers[$class2->getName()] = $obs;
			}
		}
		$this->observers = $observers;
		return $this;
	}

	/**
	 * Notify an Storage
	 * @param Storage $obsle
	 * @link http://www.php.net/manual/en/splsubject.notify.php
	 * @return Storage 
	 */
	function notify () {
		foreach ($this->observers as $obs) {
			$obs->update($this);
		}  
		return $this;
	}
	/**
	 * @return ArrayObject
	 * Enter description here ...
	 */
	public function observers() {
		return $this->observers;
	}
	/**
	 * @return Storage
	 * @param Visitorer
	 * @see models/visitorer/packages\models\visitorer.Visitable::visit()
	 */
	final function visit(Visitorer $vsr) {
	    try{
		  $class = new ReflectionClass($this);
	    }
		catch (Exception $e){
		  throw new StorageVisitException("Невозможно получить класс объекта"); 
		}
		$method = "visit".$class->getShortName();
		
		try{
		  $class = new ReflectionClass($vsr);
		}
		catch (Exception $e){
		  throw new StorageVisitException("Невозможно получить класс объекта"); 
		}
		//if (!($class->hasMethod($method)))
		//  throw new StorageVisitException("Невозможно получить доступ к методу [$method]"); 
		$vsr->$method($this);
		return $this;		
	}
	/**
	 * @return Storage
	 * Enter description here ...
	 */
	function getStorage () {
		return $this;
	}
	/**
	 * @return mixed
	 * Enter description here ...
	 * @param string $mn
	 * @param array $args
	 */
	function __call($mn,$args) {
	  foreach ($this->observers as $obr) {
	    try{
	      $class = new ReflectionClass($obr);
	    }
		catch (Exception $e){
		  throw new StorageCallException("Невозможно получить класс объекта"); 
		}
	    if ( $class->hasMethod($mn) ){
	      $method = $class->getMethod($mn);
	      $args []= $this;
	      return $method->invokeArgs($obr,$args);
	    } 
	  }
	}
	
}