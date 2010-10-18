<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Autoloader.
function cinisis_autoload($class) {
  $base = dirname(__FILE__) .'/';

  if (strstr($class, 'Db')) {
    $file = 'classes/backends/'. $class .'.php';
  }
  elseif (strstr($class, 'Iterator')) {
    $file = 'classes/iterators/'. $class .'.php';
  }
  elseif (strstr($class, 'Helper')) {
    $file = 'classes/helpers/'. $class .'.php';
  }
  else {
    $file = 'classes/'. $class .'.php';
  }

  if (file_exists($base . $file)) {
    require_once $base . $file;
  }
}

// Register autoloader.
spl_autoload_register("cinisis_autoload");

?>
