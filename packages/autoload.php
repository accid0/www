<?php
function __autoload($class) {
  // convert namespace to full file path
  $class = str_replace('\\', '/', $class) . '.php';
  try {
    include($class);
  }
  catch (Exception $e){
    throw new Exception("[autoload: $class] " . $e);
  }
}