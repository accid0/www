<?php
namespace packages\view\plugins;

use packages\models\observer\Observable;

use packages\view\expression\Expression;

use packages\view\expression\TemplateExpression;

use packages\models\observer\Observer;

use ArrayObject, Exception;

class DumpObserver implements Observer {
  /**
   * - здесь хранится дамп или NULL
   * @var mixed $dumpResult
   */
  private $dumpResults;
	/**
	 * Метод выполняется при поступленни уведомления с обьекта акцептора
	 * @param subject Observable <p> Обьект, который связан с акцептором данного наблюдателя,
	 * может иметь своих наблюдателей , параметры которых могут понадобиться данному наблюдателю.
	 * @todo
	 * 	-Этот метод может проводить проверки и вызывать метод init 
	 * @see PluginObserver::init()
	 * @see Storage::notify()
	 * @return void 
	 */
	public function update (Observable $subject){
	  
	}
	/**
	 * @see PluginObserver::update()
	 * @todo
	 * 	-Инициализацию данных плагина проводить в этом методе или в дочернем
	 * @param Observer $obs
	 * @return void
	 */
	public function init (Observer $obs){
	  
	}
	/**
	 * @param string $key
	 * @param Expression $exn
	 * @return mixed Возвращает дамп сохраненный в данном наблюдателе
	 * @throws Exception
	 */
	function getDumpResult($key, Expression $exn){
	  if ( !is_null($this->dumpResults[$key]))
        return $this->dumpResults[$key];
      return NULL;
	}
	/**
	 * Метод сохраняет дамп в данном наблюдателе
	 * @return void
	 * @param string $key
	 * @param mixed $dr дамп
	 * @param Expression $exn Вызывавший акцептор
	 */
	function  setDumpResult( $key , $dr , Expression $exn ){
      $this->dumpResults[$key] = $dr;
	}
	/**
	 * 
	 * Enter description here ...
	 */
	function __construct(){
	  $this->dumpResults = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
	}
}
