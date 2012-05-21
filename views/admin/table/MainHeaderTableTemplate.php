<?php
/**
*@name controllers\admin\table\MainHeaderTableTemplate.php
*@packages models
*@subpackage application
*@author Andrew Scherbakov
*@version 1.0
*@copyright created  2012 - 05 May - 14 Mon
*/
namespace views\admin\table;
use views\admin\MainHeaderTemplate;
use packages\models\db\DbTableColumnsCollection;
class MainHeaderTableTemplate extends MainHeaderTemplate{
	/**
	*@todo Укажите файлы шаблонов относительно параметров запроса
	*@var array
	*/
	protected $templates = array(
	);
	/**
	 * @todo query user
	 */
	public function user( $controller){
      $controller->data = new DbTableColumnsCollection(array(
        'User' => array(
          'НН' => 'userId',
          'Имя' => 'uname',
          'UserRole' => array(
            'UserGroup' => array(
              'Роли' => 'groupname'
            )
          )
        )
      ));
      $controller->title = 'Пользователи';
	}
}