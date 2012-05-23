<?php
/**
*@name modules\form\FormObserver.php
*@packages models
*@subpackage plugin
*@author Andrew Scherbakov
*@version 1.0
*@copyright created
*/
namespace modules\form;
use packages\models\db\ColumnsInfoCollection;

use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use packages\models\observer\Observer;
class FormObserver extends PluginObserver {
  /**
  * (non-PHPdoc)
  * @see packagesiew\plugins.PluginObserver::install()
  */
  protected function install(){
  }
  /**
  * @param Expression $subject
  */
  protected function doExecute(ColumnsInfoCollection $form, 
    Expression $subject){
  }
  /**
  * (non-PHPdoc)
  * @see packagesiew\plugins.PluginObserver::init()
  */
  function init(Observer $obs){
  }
  /**
  *@todo // constructor plugin
  */
  function __construct(){
  	parent::__construct();
  }
}