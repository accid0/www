<?php
/**
 * 
 * Enter description here ...
 * @author Andrew Scherbakov
 * @todo оптимально реализовать кэширование преобразованием xml 
 * в код пхп, где создаются классы с данными а они в свою очередь 
 * расширяют хелпер с его функционалом. Так чтобы не изменился
 * доступ к полям настроек. Проблема в том, что пока нет приемлемого
 * способа созздания множества тегов с одинаковыми именами
 * @todo основная проблема именно этого способа работы с xml это
 * отсутствие доступного способа встроить прямо в этот класс способ
 * синхронизации двух разных объектов этого класса, если использование
 * их не идентично, поиск в дереве основного объекта по ключу нужного
 * узла, чтобы перерисовать его считаю очень ресурсоемким.
 */
namespace packages\models\application;
use packages\models\exception\ApplicationHelperException;
use packages\models\factory\AbstractFactory;
use SimpleXMLElement, xPDOCacheManager, Iterator, ArrayAccess;
use Exception, IteratorIterator, Serializable, xPDO;
class ApplicationHelper implements Helper, Serializable{
  /**
   * 
   * Enter description here ...
   * @var xPDOCacheManager
   */
  private static $cache = NULL;
  /**
   * 
   * Enter description here ...
   * @var SimpleXMLElement
   */
  protected static $xml = NULL;
  /**
   * 
   * Enter description here ...
   * @var ApplicationHelper
   */
  private $parent = NULL;
  /**
   * 
   * Enter description here ...
   * @var array
   */
  private $children = array();
  /**
   * 
   * Enter description here ...
   * @var array
   */
  private $childsObjs = array();
  /**
   * 
   * Enter description here ...
   * @var array
   */
  private $attributes = array();
  /**
   * @var string $nameNode
   */
  private $nameNode = '';
  /**
   * 
   * Enter description here ...
   * @var string
   */
  private $valueNode = '';
  /**
   * 
   * Enter description here ...
   * @var bool
   */
  private $init = FALSE;
  /**
   * 
   * Enter description here ...
   * @var string
   */
  protected $xmlFile = 'packages/application/config.xml';
  /**
   * 
   * Enter description here ...
   * @var string
   */
  protected $xPath = '';
  /**
   * 
   * Enter description here ...
   * @return xPDOCacheManager
   */
  private function getCache(){
    if ( self::$cache == NULL){
      self::$cache = AbstractFactory::getInstance(
        'packages\\models\\db\\DbPersistenceFactory')->getCacheManager();
    }
    return self::$cache;
  }
  /**
   * @todo log file for debug
   * @param string $msg
   */
  private function log( $msg){
    AbstractFactory::getInstance(
      'packages\\models\\db\\DbPersistenceFactory')->getxPDO()->log(
      xPDO::LOG_LEVEL_DEBUG, $msg);
  }
  /**
   * 
   * Enter description here ...
   * @param bool $exn
   * @param string $msg
   * @throws Exception
   */
  private function ensure( $exn, $msg){
    if ( $exn) throw new Exception($msg);
  }
  /**
   * 
   * Enter description here ...
   */
  private function init( ){
    if( !($data = $this->getCache()->get( $this->nameNode)) ){
      $this->log( <<<EOL
      
      [ApplicationHelper : $this->nameNode] node not exists. 
      Processing loading node from xml file.
EOL
      );
      while(!$this->getCache()->lock(self::APPLICATION_HELPER_TAG))
        usleep(1000);
      $this->cache();
      $this->getCache()->deleteByTag( Helper::APPLICATION_COMMON_TAG);
      $this->getCache()->releaseLock(self::APPLICATION_HELPER_TAG);
      return;
    }
    $this->children = $data['children'];
    $this->attributes = $data['attributes'];
    $this->valueNode = $data['value'];
    foreach ( $this->childsObjs as $tag => $objs){
      foreach ( $objs as $i => $obj){
        $obj->reset( $this->children[$tag][$i]);
        $obj->access();
      }
    }
  }
  /**
   * @return SimpleXMLElement
   */
  private function _getXml(){
    if ( is_null(self::$xml)){
      $this->ensure( !file_exists( $this->xmlFile) , "Не найден файл конфигурации");
      $options = @simplexml_load_file( $this->xmlFile);
      $this->ensure( !$options, "Не валидный файл xml");
      if ( !empty( $this->xPath)){
        $options = $options->xpath( $this->xPath);
        $options = $options[0];
      }
      self::$xml = $options;
    }
    $this->ensure( is_null( self::$xml), 
    	"[ApplicationHelper : $this->xmlFile] Xml не загружен");
    return self::$xml;
  }
  /**
   * 
   * Enter description here ...
   */
  private function loadXml( $nameNode , $xml ){
    $children = array();
    $attributes = array();
    if ( $node = $this->getCache()->get( $nameNode)){
      foreach ($xml as $name => $child){
        //print "[$name]>>>";
      //print_r($xml->$name);
      //print '----------------------------------------------------------------------<br/>';
        $d = each( $node['children'][$name]);
        $this->loadXml( $d['value'], $child);
      }
      return ;
    }
    foreach( $xml as $name => $child){
      $key = substr( md5(microtime()), 0, 10);
      $this->loadXml( $key, $child);
      $children [ (string)$name][]= $key;
    }
    foreach( $xml->attributes() as $name => $child){
      $attributes [$name]= (string)$child;
    }
    $value = (string)$xml;
    $result = array( 'children' => $children, 'attributes' => $attributes, 'value' => $value);
    $this->getCache()->add( $nameNode, $result, array(self::APPLICATION_HELPER_TAG));
  }
  /**
   * 
   * Enter description here ...
   * @param SimpleXMLElement $one
   * @param SimpleXMLElement $two
   * @return bool
   */
  private function compareNode( SimpleXMLElement $one, 
    SimpleXMLElement $two){
    if ( $one->getName() == $two->getName()){
      if ( $one->attributes()->count() == 0 && 
        $two->attributes()->count() == 0 && 
        $one->children()->count() == 0 &&
        $two->children()->count() == 0 ){
          if ((string)$one != (string)$two) return FALSE;
            else return TRUE;
      }
      $onearr = array();
      $twoarr = array();
      foreach ( $one->attributes() as $a => $b){
        $onearr [(string)$a]= (string)$b;
      }
      foreach ( $two->attributes() as $a => $b){
        $twoarr [(string)$a]= (string)$b;
      }
      if ( $onearr == $twoarr ) return TRUE;
    }
    return FALSE;
  }
  /**
   * 
   * Enter description here ...
   * @param SimpleXMLElement $dest
   * @param SimpleXMLElement $res
   */
  private function _mergeXml( SimpleXMLElement $dest, 
    SimpleXMLElement $res){
    foreach ( $res->children() as $rc){
      $find = FALSE;
      foreach ( $dest->children() as $dc){
        if ( $this->compareNode($dc, $rc)) {
          $find = TRUE;
          $this->_mergeXml($dc, $rc);
        }
      }
      if (!$find){
        if ( $rc->attributes()->count() != 0){
          $child = $dest->addChild( $rc->getName());
          foreach ( $rc->attributes() as $a => $b){
            $child->addAttribute( $a, $b);
          }
        }
        else 
          $child = $dest->addChild( $rc->getName(), (string)$rc);
        $this->_mergeXml( $child, $rc);
      }
    }
  }
  /**
   * 
   * Enter description here ...
   * @return bool
   */
  private function save(){
    $result = FALSE;
    if ( !is_null(self::$xml)){
      self::$xml->asXml($this->xmlFile);
      $result = TRUE;
    }
    return $result;
  }
  /**
   * 
   * Enter description here ...
   * @param string $xml
   * @param ApplicationHelper
   */
  public function __construct( $parent = NULL){
    $this->parent = $parent;
    if ( $parent === NULL)
      $this->nameNode = self::APPLICATION_HELPER_ROOT;
    $parent = $this;
    $this->xPath = '';
  }
  /**
   * 
   * Enter description here ...
   * @param string $file
   */
  public function setXmlFile( $file){
    $this->xmlFile = $file;
  }
  /**
   * 
   * Enter description here ...
   * @param string $xpath
   */
  public function setXPath( $xpath){
    $this->xPath = $xpath;
  }
  /**
   * 
   * Enter description here ...
   * @param string $name
   */
  public function reset( $name){
    $this->nameNode = $name;
    $this->init = FALSE;
  }
  /**
   * 
   * Enter description here ...
   */
  public function access(){
    if (!$this->init) $this->init();
    $this->init = TRUE;
  }
  /**
   * 
   * Enter description here ...
   * @param string $key
   * @return Helper
   */
  public function replecate($key){
    $result = new self;
    $result->reset( $this->nameNode);
    $this->getCache()->add( $key, $this->nameNode, 
      array( self::APPLICATION_COMMON_TAG));
    return $result;
  }
  /**
   * 
   */
  public function cache(){
    if ( is_null($this->parent)){
      self::$xml = NULL;
      $this->loadXml( $this->nameNode, $this->getXml());
      $this->reset($this->nameNode);
      $this->access();
      
    }
    else $this->parent->cache();
  }
  /**
   * @todo serialized data
   * @return string
   */
  public function serialize(){
    $data = array(
      'key' => $this->nameNode,
      'file' => $this->xmlFile,
      'xpath' => $this->xPath
    );
    return serialize($data); 
  }
  /**
   * @todo deserialized data
   * @param mixed $data
   */
  public function unserialize( $data){
    $data = unserialize( $data);
    $this->setXmlFile( $data['file']);
    $this->setXPath( $data['xpath']);
    $this->reset( $data['key']);
  }
  /**
   * 
   * Enter description here ...
   * @param string $key
   * @return SimpleXMLElement
   * 
   */
  public function getXml(  $key = NULL){
    if ( !is_null( $this->parent)){
      $xml = $this->parent->getXml( $this->nameNode);
    }
    else  $xml = $this->_getXml();
    if ( is_null($key))  return $xml;
    $keyNode = '';
    $ikey = 0;
    foreach ( $this->children as $k => $cs){
      foreach ($cs as $i => $kk){
        if ( $kk == $key){
          $keyNode = $k;
          $ikey = $i;
        }
      }
    }
    $i = 0;
    foreach ($xml as $name => $node){
      if ( $name == $keyNode && $i == $ikey){
        return $node;
      }
      elseif ( $name == $keyNode) $i++;
    }
  }
  /**
   * (non-PHPdoc)
   * @see packages\models\application.Helper::mergeXml($res)
   */
  public function mergeXml(SimpleXMLElement $res){
    while(!$this->getCache()->lock(self::APPLICATION_HELPER_TAG))
      usleep(1000);
    self::$xml =NULL;
    $this->_mergeXml( $this->getXml(), $res);
    $this->save();
    $this->clean();
    $this->cache();
    $this->getCache()->releaseLock(self::APPLICATION_HELPER_TAG);
  }
  /**
   * (non-PHPdoc)
   * @see packages\models\application.Helper::find($tag, $attr, $value)
   */
  public function find( $tag, $attr, $value){
    $result = NULL;
    foreach ( $this->$tag as $obj)
      if ( $obj[$attr] === $value){
        return $obj;
      }
    return $result;
  }
  /**
   * (non-PHPdoc)
   * @see packages\models\application.Helper::findTree($tag, $attr, $value)
   */
  public function findTree( $tag, $attr = NULL, $value = NULL){
    $this->access();
    $result = array();
    foreach ( $this->children as $name => $array){
      foreach ( $this->$name as $obj){
        if ( $name === $tag && ( $attr === NULL || $obj[$attr] === $value)){
          $result []= $obj;
        }
        foreach ( $obj->findTree( $tag, $attr, $value) as $o){
            $result []= $o;
        }
      }
    }
    return new ApplicationHelperIterator( $result);
  }
  /**
   * (non-PHPdoc)
   * @see packages\models\application.Helper::addChild($name)
   */
  public function addChild( $name){
    while(!$this->getCache()->lock(self::APPLICATION_HELPER_TAG))
      usleep(1000);
    self::$xml =NULL;
    $this->getXml()->addChild( $name);
    $this->save();
    $this->clean();
    $this->cache();
    $this->getCache()->releaseLock(self::APPLICATION_HELPER_TAG);
    return end(end( $this->$name));
  }
  /**
   * (non-PHPdoc)
   * @see packages\models\application.Helper::clean()
   */
  public function clean(){
    return $this->getCache()->delete($this->nameNode);
  }
  /**
   * (non-PHPdoc)
   * @see packages\models\application.Helper::__get($key)
   */
  public function __get($key){
    $this->access();
    if ( !isset( $this->children[$key]))  return new ApplicationHelperIterator(array());
    if (!isset($this->childsObjs[$key]))  $this->childsObjs[$key] = array();
    $countO = count($this->childsObjs[$key]);
    $countC = count($this->children[$key]);
    if ( $countO < $countC){
      for( $i = $countO; $i<$countC; ++$i){
        $obj = new self($this);
        $obj->reset($this->children[$key][$i]);
        $this->childsObjs [$key][$i]= $obj;
      }
    }
    return new ApplicationHelperIterator( $this->childsObjs [$key]);
  }
  /**
   * (non-PHPdoc)
   * @see packages\models\application.Helper::__toString()
   */
  public function __toString(){
    $this->access();
    return $this->valueNode;
  }
  /**
   * (non-PHPdoc)
   * @see ArrayAccess::offsetSet()
   */
  public function offsetSet($offset, $value) {
    $this->ensure(is_null($offset), "Задайте имя аттрибута");
    while(!$this->getCache()->lock(self::APPLICATION_HELPER_TAG))
      usleep(1000);
    self::$xml =NULL;
    $node = $this->getXml();
    if ( isset( $this->attributes[$offset]))  $node[$offset] = $value;
    else $node->addAttribute( $offset, $value);
    $this->save();
    $this->clean();
    $this->cache();
    $this->getCache()->releaseLock(self::APPLICATION_HELPER_TAG);
  }
  /**
   * (non-PHPdoc)
   * @see ArrayAccess::offsetExists()
   */
  public function offsetExists($offset) {
    $this->access();
    return isset($this->attributes[$offset]);
  }
  /**
   * (non-PHPdoc)
   * @see ArrayAccess::offsetUnset()
   */
  public function offsetUnset($offset) {
    $this->access();
    unset($this->attributes[$offset]);
  }
  /**
   * (non-PHPdoc)
   * @see ArrayAccess::offsetGet()
   */
  public function offsetGet($offset) {
    $this->access();
    return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null;
  }
  /**
   * (non-PHPdoc)
   * @see packages\models\application.Helper::__isset($key)
   */
  public function __isset( $key){
    $this->access();
    return isset( $this->children[$key]);
  }
}