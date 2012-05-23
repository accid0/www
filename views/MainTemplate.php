<?php
/**
*@name MainTemplate.php
*@packages 
*@subpackage
*@author Home
*@version 
*@copyright created 13.05.2012
*/
namespace views;
use packages\models\factory\AbstractFactory;

use packages\view\expression\PluginExpression;

class MainTemplate extends PluginExpression{
  protected $templates = array(
    'admin' => 'admin/mws/admin.tpl',
    'add' => 'admin/mws/tpl/form/add.tpl',
    'delete' => 'admin/mws/tpl/form/delete.tpl',
    'refresh' => 'admin/mws/tpl/form/update.tpl',
    'hello' => 'admin/mws/tpl/hello.tpl'
  );
  /**
   * @param Builder $cntr
   */
  private function initTemplate( $cntr){
    $cntr->lang = $cntr->lang(NULL);
    $cntr->applicationHelper = $cntr->applicationHelper();
  }
  /**
   * @param Builder $cntr
   */
  public function cache( $cntr){
    $cntr->applicationHelper()->clean();
  }
  /**
   * @param Builder $cntr
   */
  public function phpinfo( $cntr){
    phpinfo();
  }
  /**
   * @param Builder $cntr
   */
  public function admin( $cntr){
    $this->initTemplate( $cntr);
  }
  /**
   * Print json answer
   * @param Builder $cntr
   */
  public function datatable( $cntr){
    print $cntr->datatable();
  }
}