<?php
/**
 * Cinisis - Isis db reading tool.
 *
 * @TODO: check what happens if theres a field and subfields with repetition.
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

/**
 * Merge fields in a single cel.
 *
 * @param $data
 *   Array with field data.
 *
 * @param $field
 *   Field name.
 *
 * @return
 *   Cel with merged fields.
 */
function merge_fields($data, $field) {
  $cel = '';
  $sep = (count($data) > 1) ? '; ': '';
  foreach ($data as $subkey => $subvalue) {
    $cel = $cel . $data[$subkey][$field] . $sep;
  }

  return $cel;
}

// Import Cinisis Library.
require_once '../index.php';

// Get a db instance.
$isis = new Cinisis();

// Test connection.
if ($isis->db) {
  // Get format and number of entries.
  $entries = $isis->db->entries();
  $format  = $isis->db->format;

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
  for ($n = 1; $n <= $entries; $n++) {
    $result = $isis->db->read($n);

    if ($result) {
      // Filter results.
      array_walk_recursive($result, 'filter');

      foreach ($format['fields'] as $field) {
        if (is_array($result[$field['name']])) {
          // Print main field if needed.
          if (is_array($result[$field['name']][0])) {
            echo csv();
          }
          else {
            echo csv($result[$field['name']][0]);
          }
        }
        else {
          echo csv($result[$field['name']]);
        }
        if (is_array($field['subfields'])) {
          foreach ($field['subfields'] as $key => $value) {
            // Deals with subfield repetition.
            if (isset($result[$field['name']][0][$value])) {
              echo csv(merge_fields($result[$field['name']], $value));
            }
            else {
              echo csv($result[$field['name']][$value]);
            }
          }
        }
      }

      // New roll.
      echo "\n";
    }
  }
}
