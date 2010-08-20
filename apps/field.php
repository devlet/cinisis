<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Get input data.
$entry = CinisisHttpHelper::getNumericArg('entry');
$fid   = CinisisHttpHelper::getNumericArg('fid');

// Draw the document.
$display = new CinisisDisplayHelper('Field finder');
$form    = $display->formInputText('entry', $entry);
$form   .= $display->formInputText('fid', $fid);
$display->form($form, basename(__FILE__));

// Get a db instance.
$isis = new IsisFinder();

// Setup database and entry number.
if ($isis) {
  // Query database.
  $field                = $isis->getFieldArray($fid);
  list($entry, $result) = $isis->nextField($field, $entry);

  // Navigation bar.
  $display->navbar($entry, $isis->entries, 'field.php', '&fid='. $fid);

  // Format output.
  echo "<pre>\n";
  echo "Selected field: $fid: ". $field['name'] ."\n";
  echo "Showing entry ". $display->entryLink($entry) ." from ". $isis->entries ." total entries.\n";
  echo "Repetitions found: ". count($result[$field['name']]) .".\n";
  echo "\n";
  print_r($result[$field['name']]);
  echo '</pre>';
}

$display->footer();
