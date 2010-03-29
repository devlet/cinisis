<?php
/**
 * Database procedures.
 */

// Import Malete Library
require 'contrib/malete/php/Isis.php';

// Import Spyc
include('contrib/spyc/spyc.php');

// Import database classes
require 'isis.php';

// Test database connection.
$schema = Spyc::YAMLLoad('schemas/anu10.yaml');
$db     = new MaleteDb($schema);
if ($db) {
  $result = $db->read(4);
  echo '<pre>';
  print_r($result);
  echo '</pre>';
}

?>
