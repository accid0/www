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
    'hello' => 'admin/mws/tpl/hello.tpl'
  );
  /**
   * (non-PHPdoc)
   * @see packages\view\expression.PluginExpression::initialize()
   */
  protected function initialize( Visitorer $cntr){
    $cntr->lang = $cntr->lang(NULL);
    $cntr->helper = $cntr->helper();
  }
  /**
   * @param Visitorer $cntr
   */
  public function cache( Visitorer $cntr){
    $cntr->helper->clean();
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
    $cntr->datatable();
  }
  /**
   * add form to db
   * @param Visitorer $cntr
   */
  public function add( Visitorer $cntr){
    $cntr->form('create');
  }
  /**
   * add form to db
   * @param Visitorer $cntr
   */
  public function refresh( Visitorer $cntr){
    $cntr->form('update');
  }
  /**
   * 
   * @param Visitorer $cntr
   */
  public function delete( Visitorer $cntr){
    $cntr->form('delete');
  }
}