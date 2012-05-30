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
use packages\models\db\DbForm;

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
   * @param array $array
  * @param Expression $subject
  */
  protected function doExecute( $var, Expression $subject){
    $this->getPlugin('user');
    $request = $this->getPlugin('request');
    $form = NULL;
    if ( isset( $request->serializeForm) && is_string($var)){
      $key = $request->serializeForm;
      $form = new DbForm( json_decode($key, TRUE));
      switch ($var){
        case 'create':
          foreach ( $form as $key => $field){
            if ( isset( $request->$key))  $form->$key = $request->$key;
          }
          $form->save();
          break;
        case 'update':
          $pkName = $form->getPk();
          $this->ensure( !isset( $request->$pkName), 
          	"Не найден первичный ключ [$pkName]");
          $form->get( $pkName, $request->$pkName);
          foreach ( $form as $key => $field){
            if ( isset( $request->$key))  $form->$key = $request->$key;
          }
          $form->save();
          break;
        case 'delete':
          $pkName = $form->getPk();
          $this->ensure( !isset( $request->$pkName), 
          	"Не найден первичный ключ [$pkName]");
          $form->get( $pkName, $request->$pkName);
          $form->delete();
          break;
        default:;
      }
    }
    elseif ( !empty($var))  $form = new DbForm($var);
    $this->setResult( $form);
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