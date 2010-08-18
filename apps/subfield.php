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
$display->form($form, basename(__FILE__));

// Get a db instance.
$isis = new IsisFinder();

// Setup database and entry number.
if ($isis) {
  // Query database.
  $field_name           = $isis->getFieldName($field);
  $subfield_name        = $isis->getSubfieldName($field, $subfield);
  list($entry, $result) = $isis->nextSubfield($entry, $field_name, $subfield_name);

  // Navigation bar.
  $display->navbar($entry, $isis->entries, $repetition, '&field='. $field . '&subfield='. $subfield);

  // Format output.
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
