<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Get input data.
$entry = CinisisHttpHelper::get_numeric_arg('entry');
$field = CinisisHttpHelper::get_numeric_arg('field');

// Draw the document.
$display = new CinisisDisplayHelper('Repetition finder');
$form    = $display->form_input_text('entry', $entry);
$form   .= $display->form_input_text('field', $field);
$display->form($form, 'repetition.php');

// Get a db instance.
$isis = new CinisisDb();

// Setup database and entry number.
if ($isis->db) {
  // Get the number of entries.
  $field_name = $isis->db->format['fields'][$field]['name'];
  $entries    = $isis->db->entries();
  $entry--;

  // Query database.
  do {
    $result = $isis->db->read(++$entry);
    if ($entry == $entries) {
      break;
    }
  } while (!isset($result[$field_name]) || count($result[$field_name]) < 2);

  // Navigation bar.
  $display->navbar($entry, $entries, $repetition, '&field='. $field);

  // Format output.
  echo "<pre>\n";
  echo "Selected field: $field: $field_name.\n";
  echo "Showing entry $entry from $entries total entries.\n";
  echo "Repetitions found: ". count($result[$field_name]) .".\n";
  echo "\n";
  print_r($result[$field_name]);
  echo '</pre>';
}

$display->footer();
?>
