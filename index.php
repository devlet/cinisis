<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import Malete Library.
require_once 'contrib/malete/php/Isis.php';

// Import Spyc.
require_once 'contrib/spyc/spyc.php';

// Autoloader.
function cinisis_autoload($class) {
  if (strstr($class, 'Iterator')) {
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
