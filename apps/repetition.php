<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Get input data.
$entry = CinisisHttpHelper::get_numeric_arg('entry');
$fid   = CinisisHttpHelper::get_numeric_arg('fid');

// Draw the document.
$display = new CinisisDisplayHelper('Repetition finder');
$form    = $display->form_input_text('entry', $entry);
$form   .= $display->form_input_text('fid', $fid);
$display->form($form, basename(__FILE__));

// Get a db instance.
$isis = new IsisFinder();

// Setup database and entry number.
if ($isis) {
  // Query database.
  $field                = $isis->getFieldArray($fid);
  list($entry, $result) = $isis->nextRepetition($entry, $field);

  // Navigation bar.
  $display->navbar($entry, $isis->entries, $repetition, '&fid='. $fid);

  // Format output.
  echo "<pre>\n";
  echo "Selected field: $fid: ". $field['name'] ."\n";
  echo "Showing entry ". $display->entry_link($entry) ." from $entries total entries.\n";
  echo "Repetitions found: ". count($result[$field['name']]) .".\n";
  echo "\n";
  print_r($result[$field['name']]);
  echo '</pre>';
}

$display->footer();
