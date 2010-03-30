<?php
/**
 * Isis db migration tool.
 */

// Import Malete Library.
require 'contrib/malete/php/Isis.php';

// Import Spyc.
require 'contrib/spyc/spyc.php';

// Import Isis interface.
require 'interface.php';

// Autoloader.
function __autoload($class) {
  require_once 'classes/' .$class. '.php';
}

// Load configuration.
$config = Spyc::YAMLLoad('config/config.yaml');

// Load database schema.
$schema = Spyc::YAMLLoad('schemas/'. $config['database'] .'.yaml');

// Setup database connection.
$implementation = $config['implementation'] .'Db';
$db             = new $implementation($schema);

// Test connection.
if ($db) {
  $result = $db->read(1);
  $rows   = $db->rows();

  // Format output.
  echo '<pre>';
  echo "Rows: $rows\n";
  print_r($result);
  echo '</pre>';
}

?>
