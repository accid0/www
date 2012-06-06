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
          'логин' => 'uname',
          'имя' => 'name',
          'почта' => 'email',
          'www' => 'url',
          'аватар' => 'user_avatar',
          'регистрация' => 'user_regdate',
          'откуда' => 'user_from',
          'интересы' => 'user_interests',
          'рождение' => 'user_birthday',
          'пароль' => 'pass',
          'показыватьИмейл' => 'user_viewemail',
          'инкогнито' => 'invisible',
          'информировать' => 'recieve_newsletter',
          'страна' => 'country',
          'персональность' => 'person',
          'телефон' => 'phone',
          'мобильный' => 'phone_mobile',
          'факс' => 'fax',
          'фамилия' => 'lastname',
          'zip' => 'zip',
          'улица' => 'street',
          'паблик' => 'show_public',
          'компания' => 'company',
          'аська' => 'user_icq',
          'аим' => 'user_aim',
          'скайп' => 'user_skype',
          'UserRole' => array(
            'UserGroup' => array(
              'роли' => 'groupname'
            )
          )
        )
      ));
	}
}