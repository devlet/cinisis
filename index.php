<?php
/**
 * CinIsis - Isis db reading tool.
 */

// Import Malete Library.
require_once 'contrib/malete/php/Isis.php';

// Import Spyc.
require_once 'contrib/spyc/spyc.php';

// Import Isis interface.
require_once 'interface.php';

// Autoloader.
function cinisis_autoload($class) {
  require_once 'classes/' .$class. '.php';
}

// Register autoloader.
spl_autoload_register("cinisis_autoload");

// Get a db instance.
$isis = new CinIsis();

// Test connection.
if ($isis->db) {
  $result = $isis->db->read(1);
  $rows   = $isis->db->rows();

  // Format output.
  echo '<pre>';
  echo "Rows: $rows\n";
  print_r($result);
  echo '</pre>';
}

?>
