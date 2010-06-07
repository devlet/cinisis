<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import Malete Library.
require_once 'contrib/malete/php/Isis.php';

// Import Spyc.
require_once 'contrib/spyc/spyc.php';

// Import Isis interface.
require_once 'interface.php';

// Autoloader.
function cinisis_autoload($class) {
  require_once 'classes/'. $class .'.php';
}

// Register autoloader.
spl_autoload_register("cinisis_autoload");

// Reference to the base path.
$cinisis_basedir = dirname(__FILE__);

?>
