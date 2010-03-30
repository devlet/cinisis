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

// Test database connection.
$schema = Spyc::YAMLLoad('schemas/anu10.yaml');
//$db   = new MaleteDb($schema);
$db     = new PhpIsisDb($schema);
if ($db) {
  $result = $db->read(1);
  //$result = $db->rows();
  echo '<pre>';
  print_r($result);
  echo '</pre>';
}

?>
