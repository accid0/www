<?php
/**
*@name controllers\admin\MainContainerTemplate.php
*@packages models
*@subpackage application
*@author Andrew Scherbakov
*@version 1.0
*@copyright created  2012 - 05 May - 14 Mon
*/
namespace views\admin;
use views\MainTemplate;
class MainContainerTemplate extends MainTemplate{
	/**
	*@todo Укажите файлы шаблонов относительно параметров запроса
	*@var array
	*/
	protected $templates = array(
	  'table' => 'admin/mws/tpl/table/table.tpl'
	);
}