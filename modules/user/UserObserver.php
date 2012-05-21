<?php
namespace modules\user;

use packages\models\observer\Observer;

use packages\view\plugins\AuthPlugin;

use packages\view\expression\Expression;

use packages\view\plugins\PluginObserver;

use xPDO;
class UserObserver extends PluginObserver implements AuthPlugin{
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $errorMsg = NULL;
  /**
   * 
   * @param string $error
   */
  private function setError( $error){
    if ( is_null($this->errorMsg))
      $this->errorMsg = $error;
  }
  /**
   * 
   * @param int $length
   * @return string
   */
  private function salt( $length = 6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
      $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::install()
   */
  protected function install(){
    $db = $this->getDbPersistenceFactory();
    $urole = $db->newObject("Userrole");
    $user = $db->newObject('User');
    $user->userId = 1;
    $user->uname = 'Администратор';
    $user->salt = $this->salt();
    $user->passtemp = md5(md5('администратор') . md5($user->salt));
    $ugroup = $db->newObject('Usergroup');
    $ugroup->ugroup = 1;
    $ugroup->groupname = 'Администраторы';
    $urole->addOne($user);
    $urole->addOne($ugroup);
    $urole->save();
    //
    $urole = $db->newObject("Userrole");
    $user = $db->newObject('User');
    $user->userId = 2;
    $user->uname = 'Гость';
    $ugroup = $db->newObject('Usergroup');
    $ugroup->ugroup = 2;
    $ugroup->groupname = 'Гости';
    $urole->addOne($user);
    $urole->addOne($ugroup);
    $urole->save();
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::init()
   */
  function init( Observer $obs){
    $request = $this->getPlugin('request');
    $cookies = $this->getPlugin('cookies');
    $db = $this->getDbPersistenceFactory();
    if ( isset($request->username)){
      $query = $db->newQuery("User");
      $query->where( array(
        "User." . $request->loginField => $request->username  
      ));
      $user = $db->getObjectGraph("User",
         '{"UserRole":{"UserGroup":{}}}', $query);
      if (!isset($user->passtemp))  {
    	$this->setError("Пользователь с такими данными [$request->username]
    	 не зарегистрирован");
    	return;
      }
      if ( $user->passtemp !== md5(md5($request->password) . 
      md5($user->salt))) {
    	$this->setError("Пароль не совпадает");
    	return ;
      }
      $cookies->pass = $user->passtemp;
    }
    elseif ( isset( $cookies->pass )){
      $query = $db->newQuery("User");
      $query->where( array(
        "User.passtemp" => $cookies->pass
      ));
      $user = $db->getObjectGraph("User",
         '{"UserRole":{"UserGroup":{}}}', $query);
      if ( !isset($user->passtemp) ){
    	$this->setError("Пароль не совпадает");
    	return;
      }
    }
    else{
      $query = $db->newQuery("User");
      $query->where( array(
        "User.userId" => 2
      ));
      $user = $db->getObjectGraph("User",
         '{"UserRole":{"UserGroup":{}}}', $query);
    }
     $this->setResult($user);
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.AuthPlugin::getRole()
   */
  function getRole(){
    $role = array();
    if ( $this->ensure( !is_null($this->errorMsg), $this->errorMsg))
       return ;
    foreach( $this->getResult()->UserRole as $r){
      $role []= $r->ugroup;
    }
    return $role;
  }
  /**
   * (non-PHPdoc)
   * @see packages\view\plugins.PluginObserver::doExecute()
   */
  protected function doExecute(Expression $subject){
    
  }
  /**
   * 
   * Enter description here ...
   */
  function __construct(){
    parent::__construct();
  }
}