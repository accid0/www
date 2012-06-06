<?php
/**
*@name controllers\admin\MainHeaderTemplate.php
*@packages models
*@subpackage application
*@author Andrew Scherbakov
*@version 1.0
*@copyright created  2012 - 05 May - 14 Mon
*/
namespace views\admin;
use packages\models\visitorer\Visitorer;

use views\MainTemplate;
class MainHeaderTemplate extends MainTemplate{
	/**
	*@todo Укажите файлы шаблонов относительно параметров запроса
	*@var array
	*/
	protected $templates = array(
	  'table' => 'admin/mws/tpl/table/htable.tpl',
	  'modules' => 'admin/mws/tpl/modules/scripts.tpl'
	);
  /**
   * (non-PHPdoc)
   * @see packages\view\expression.PluginExpression::initialize()
   */
	protected function initialize( Visitorer $cntr){}
}