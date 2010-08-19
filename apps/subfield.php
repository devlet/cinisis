<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Get input data.
$entry        = CinisisHttpHelper::get_numeric_arg('entry');
$field_key    = CinisisHttpHelper::get_numeric_arg('field_key');
$subfield_key = CinisisHttpHelper::get_textual_arg('subfield_key');

// Draw the document.
$display = new CinisisDisplayHelper('Subfield finder');
$form    = $display->form_input_text('entry', $entry);
$form   .= $display->form_input_text('field_key', $field_key);
$form   .= $display->form_input_text('subfield_key', $subfield_key);
$display->form($form, basename(__FILE__));

// Get a db instance.
$isis = new IsisFinder();

// Setup database and entry number.
if ($isis) {
  // Query database.
  $field                = $isis->getFieldArray($field_key);
  $subfield             = $isis->getSubfieldName($field_key, $subfield_key);
  list($entry, $result) = $isis->nextSubfield($entry, $field, $subfield);

  // Navigation bar.
  $display->navbar($entry, $isis->entries, $repetition, '&field_key='. $field_key . '&subfield_key='. $subfield_key);

  // Format output.
  echo "<pre>\n";
  echo "Selected field: $field_key: ". $field['name'] .".\n";
  echo "Selected subfield: $subfield_key: $subfield.\n";
  echo "Showing entry ". $display->entry_link($entry) ." from $entries total entries.\n";
  echo "Repetitions found: ". count($result[$field['name']]) .".\n";
  echo "\n";
  print_r($result[$field['name']]);
  echo '</pre>';
}

$display->footer();
