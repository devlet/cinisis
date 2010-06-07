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
  require_once 'classes/'. $class .'.php';
}

// Register autoloader.
spl_autoload_register("cinisis_autoload");

?>
