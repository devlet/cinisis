<?php
/**
 * Cinisis - Isis db reading tool.
 */

/**
 * Format a value for CSV output.
 *
 * @param $field
 *   Field entry.
 *
 * @return
 *   Formatted CSV field.
 */
function csv($field = NULL) {
  return '"'. preg_replace('/"/', '""', $field) .'",';
}

/**
 * Apply filters into the result.
 *
 * @param $field
 *   Field entry.
 */
function filter(&$field = NULL) {
  // Remove brackets from field content.
  $field = str_replace('<', '', $field);
  $field = str_replace('>', '', $field);
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
    $result = $isis->db->read($n);

    if ($result) {
      // Filter results.
      array_walk_recursive($result, 'filter');

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
}
