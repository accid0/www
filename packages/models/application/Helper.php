<?php
/**
 * 
*@name Helper.php
*@packages models
*@subpackage application
*@author Andrew Scherbakov
*@version 1.0
*@copyright created 13.05.2012
*
*/
namespace packages\models\application;
use SimpleXMLElement, ArrayAccess;
interface Helper extends ArrayAccess{
  /**
   * Имя тэга для связи с опциями приложения
   * @var string
   */
  const APPLICATION_COMMON_TAG = 'commonApplication';
  /**
   * Имя тэга кэша
   * @var string
   */
  const APPLICATION_HELPER_TAG = 'applicationHelper';
  /**
   * Имя ключа корня кэша
   * @var string
   */
  const APPLICATION_HELPER_ROOT = 'applicationHelper';
  /**
   * Возвращает массив обьектов наследуемых от данного узла
   * с заданным ключом
   * @param string $key
   * @return ApplicationHelperIterator
   */
  function __get( $key);
  /**
   * Возвращает строковое значение текущего узла
   * @return string
   */
  function __toString();
  /**
   * Объединяет дерево значений установленных в приложении с 
   * деревом полученным из xml
   * @param SimpleXMLElement $res
   */
  function mergeXml(SimpleXMLElement $res);
  /**
   * Ищет вхождение прямого наследника с заданным ключом и 
   * заданным значением аттрибута в случае неудачи возвращает NULL
   * @param string $tag
   * @param string $attr
   * @param string $value
   * @return Helper
   */
  function find( $tag, $attr, $value);
  /**
   * 
   * Ищет вхождение наследника в поддереве с заданным ключом и 
   * заданным значением аттрибута в случае неудачи возвращает NULL
   * @param string $tag
   * @param string $attr
   * @param string $value
   * @return ApplicationHelperIterator
   */
  function findTree( $tag, $attr, $value);
  /**
   * Добавляет прямого наследника с заданным ключом
   * @param string $name
   * @return Helper
   */
  function addChild( $name);
  /**
   * Проверяет существует ли прямой наследник с заданным ключом
   * @param string $key
   */
  function __isset( $key);
  /**
   * Удаляет из кэша текущий узел, что приводит к его 
   * повторному кэшированию
   * @return bool
   */
  public function clean();
}