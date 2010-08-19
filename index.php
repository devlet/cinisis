<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Autoloader.
function cinisis_autoload($class) {
  if (strstr($class, 'Db')) {
    require_once 'classes/backends/'. $class .'.php';
  }
  elseif (strstr($class, 'Iterator')) {
    require_once 'classes/iterators/'. $class .'.php';
  }
  elseif (strstr($class, 'Helper')) {
    require_once 'classes/helpers/'. $class .'.php';
  }
  else {
    require_once 'classes/'. $class .'.php';
  }
}

// Register autoloader.
spl_autoload_register("cinisis_autoload");

?>
