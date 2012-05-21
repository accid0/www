<?php
/**
*@name controllers\admin\table\MainContainerFormTemplate.php
*@packages models
*@subpackage application
*@author Andrew Scherbakov
*@version 1.0
*@copyright created  2012 - 05 May - 14 Mon
*/
namespace views\admin\table;
use views\admin\MainContainerTemplate;
class MainContainerFormTemplate extends MainContainerTemplate{
	/**
	*@todo Укажите файлы шаблонов относительно параметров запроса
	*@var array
	*/
	protected $templates = array(
	  'user' => 'admin/mws/tpl/user/addform.tpl'
	);
}