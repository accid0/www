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
use packages\models\visitorer\Visitorer;

use views\admin\MainHeaderTemplate;
use packages\models\db\DbForm;
class MainHeaderTableTemplate extends MainHeaderTemplate{
	/**
	*@todo Укажите файлы шаблонов относительно параметров запроса
	*@var array
	*/
	protected $templates = array(
	);
  /**
   * (non-PHPdoc)
   * @see packages\view\expression.PluginExpression::initialize()
   */
	protected function initialize( Visitorer $cntr){
      $cntr->title = $cntr->lang['User'];
	}
	/**
	 * @todo query user
	 * @param Visitorer $controller
	 */
	public function user( Visitorer $controller){
      $data = $controller->form( array(
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
      $controller->data = $data;
      $controller->form = $controller->form( array(
        'User' => array(
          'Имя' => 'uname',
          'Фамилия' => 'name',
          'Почта' => 'email',
          'www' => 'url',
          
          'UserRole' => array(
            'UserGroup' => array(
              'Роли' => 'groupname'
            )
          )
        )
      ));
	}
}