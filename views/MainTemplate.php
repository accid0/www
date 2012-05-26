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

use packages\models\visitorer\Visitorer;

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
   * (non-PHPdoc)
   * @see packages\view\expression.PluginExpression::initialize()
   */
  protected function initialize( Visitorer $cntr){
    $cntr->lang = $cntr->lang(NULL);
    $cntr->applicationHelper = $cntr->applicationHelper();
  }
  /**
   * @param Visitorer $cntr
   */
  public function cache( Visitorer $cntr){
    $cntr->applicationHelper()->clean();
  }
  /**
   * @param Visitorer $cntr
   */
  public function phpinfo( Visitorer $cntr){
    phpinfo();
  }
  /**
   * Print json answer
   * @param Visitorer $cntr
   */
  public function datatable( Visitorer $cntr){
    print $cntr->datatable();
  }
}