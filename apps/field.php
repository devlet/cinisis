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
$display = new CinisisDisplayHelper('Field finder');
$form    = $display->form_input_text('entry', $entry);
$form   .= $display->form_input_text('field', $field);
$display->form($form, basename(__FILE__));

// Get a db instance.
$isis = new IsisFinder();

// Setup database and entry number.
if ($isis) {
  // Query database.
  $field_name = $isis->getFieldName($field);
  $result     = $isis->nextField($entry, $field_name);

  // Navigation bar.
  $display->navbar($entry, $isis->entries, $repetition, '&field='. $field);

  // Format output.
  echo "<pre>\n";
  echo "Selected field: $field: $field_name.\n";
  echo "Showing entry ". $display->entry_link($entry) ." from $entries total entries.\n";
  echo "Repetitions found: ". count($result[$field_name]) .".\n";
  echo "\n";
  print_r($result[$field_name]);
  echo '</pre>';
}

$display->footer();
