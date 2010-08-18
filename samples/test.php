<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Draw the document.
$display = new CinisisDisplayHelper('Isis Test');

// Get a db instance.
$isis = new Cinisis();

// Test connection.
if ($isis->db) {
  $result  = $isis->db->read(1);
  $entries = $isis->db->entries();

  // Format output.
  echo '<pre>';
  echo "Connection test:\n";
  echo "Rows: $entries\n";
  print_r($result);
  echo '</pre>';
}

$display->footer();
