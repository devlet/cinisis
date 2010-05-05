<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import Cinisis Library.
require_once '../index.php';

// Get a db instance.
$isis = new CinisisDb();

// Test connection.
if ($isis->db) {
  // Prepare output
  header("Content-type: application/text/x-csv");
  header("Content-Disposition: attachment; filename=export.csv");
	header("Pragma: no-cache");
	header("Expires: 0");

  $rows   = $isis->db->rows();
  $format = $isis->db->format;

  foreach ($format['fields'] as $field) {
    echo $field['name'] .',';
  }

  for ($n=1; $n <= $rows; $n++) {
    $result = $isis->db->read($n);
    // Format output.
    foreach ($format['fields'] as $field) {
      echo $result[$field['name']] .',';
    }
    echo "\n";
  }
}
