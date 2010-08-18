<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Get input data.
$entry    = CinisisHttpHelper::get_numeric_arg('entry');
$field    = CinisisHttpHelper::get_numeric_arg('field');
$subfield = CinisisHttpHelper::get_textual_arg('subfield');

// Draw the document.
$display = new CinisisDisplayHelper('Subfield finder');
$form    = $display->form_input_text('entry', $entry);
$form   .= $display->form_input_text('field', $field);
$form   .= $display->form_input_text('subfield', $subfield);
$display->form($form, 'subfield.php');

// Get a db instance.
$isis = new CinisisDb();

// Setup database and entry number.
if ($isis->db) {
  // Get the number of entries.
  $field_name    = $isis->db->format['fields'][$field]['name'];
  $subfield_name = $isis->db->format['fields'][$field]['subfields'][$subfield];
  $entries       = $isis->db->entries();
  $entry--;

  // Query database.
  do {
    $result = $isis->db->read(++$entry);
    if ($entry == $entries) {
      break;
    }
  } while (!isset($result[$field_name][0][$subfield_name]));

  // Navigation bar.
  $display->navbar($entry, $entries, $repetition, '&field='. $field . '&subfield='. $subfield);

  // Format output.
  $link = $display->entry_link($entry);
  echo "<pre>\n";
  echo "Selected field: $field: $field_name.\n";
  echo "Selected subfield: $subfield: $subfield_name.\n";
  echo "Showing entry ". $display->entry_link($entry) ." from $entries total entries.\n";
  echo "Repetitions found: ". count($result[$field]) .".\n";
  echo "\n";
  print_r($result[$field_name]);
  echo '</pre>';
}

$display->footer();
?>
