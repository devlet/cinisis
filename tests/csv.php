<?php
/**
 * Cinisis - Isis db reading tool.
 */

/**
 * Format a value for CSV output.
 */
function csv($field = NULL) {
  return '"'. preg_replace('/"/', '""', $field) .'",';
}

// Import Cinisis Library.
require_once '../index.php';

// Get a db instance.
$isis = new CinisisDb();

// Test connection.
if ($isis->db) {
  // Get format and number of rows.
  $rows   = $isis->db->rows();
  $format = $isis->db->format;

  // Prepare output.
  header("Content-type: application/text/x-csv");
  header("Content-Disposition: attachment; filename=export.csv");
	header("Pragma: no-cache");
	header("Expires: 0");

  // Format fields.
  foreach ($format['fields'] as $field) {
    echo csv($field['name']);
    if (is_array($field['subfields'])) {
      foreach ($field['subfields'] as $key => $value) {
        echo csv($field['name'] .': '. $value);
      }
    }
  }

  // New roll.
  echo "\n";

  // Format output.
  for ($n=1; $n <= $rows; $n++) {
    // FIXME: fbe db corruption?
    if ($n == 1494) {
      continue;
    }

    $result = $isis->db->read($n);
    foreach ($format['fields'] as $field) {
      if (is_array($result[$field['name']])) {
        echo csv();
      }
      else {
        echo csv($result[$field['name']]);
      }
      if (is_array($field['subfields'])) {
        foreach ($field['subfields'] as $key => $value) {
          echo csv($result[$field['name']][$value]);
        }
      }
    }

    // New roll.
    echo "\n";
  }
}
