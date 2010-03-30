<?php
/**
 * Isis db migration tool.
 */

// Import Malete Library.
require 'contrib/malete/php/Isis.php';

// Import Spyc.
include('contrib/spyc/spyc.php');

// Import Isis interface.
require 'interface.php';

// Autoloader.
function __autoload($class) {
  require_once 'classes/' .$class. '.php';
}

// Load database schema.
$schema = Spyc::YAMLLoad('schemas/anu10.yaml');

// Setup database connection.
$db   = new MaleteDb($schema);
//$db     = new PhpIsisDb($schema);

// Test connection.
if ($db) {
  echo '<pre>';
  $result = $db->read(1);
  //$result = $db->rows();
  print_r($result);
  echo '</pre>';
}

?>
