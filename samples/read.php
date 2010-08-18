<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Draw the document.
$display = new CinisisDisplayHelper('Isis Reader');
$display->open_table();

$configs = array(
  0 => array(
    'implementation' => 'PhpIsis',
    'database'       => 'dbname',
  ),
  1 => array(
    'implementation' => 'BiblioIsis',
    'database'       => 'dbname',
  ),
);

foreach ($configs as $config) {
  // Get a db instance.
  $isis = new CinisisDb($config);

  // Test connection.
  if ($isis->db) {
    $result  = $isis->db->read(1);
    $entries = $isis->db->entries();

    // Format output.
    echo '<td>';
    echo '<pre>';
    echo 'Implementation: '. $config['implementation'] ."\n";
    echo "Rows: $entries\n";
    print_r($result);
    echo '</pre>';
    echo '</td>';
  }
}

$display->close_table();
$display->footer();
