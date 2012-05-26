<?php
/**
*@name Router.php
*@packages models
*@subpackage application
*@author Andrew Scherbakov
*@version 1.0
*@copyright created 13.05.2012
*/
namespace packages\view\expression;

use packages\view\builder\Builder;

interface Template{
  /**
   * 
   * Enter description here ...
   * @param string $query
   * @return string
   */
  function prepareFileTemplate( $query);
}