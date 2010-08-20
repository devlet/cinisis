<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Get input data.
$entry = CinisisHttpHelper::get_numeric_arg('entry');
$fid   = CinisisHttpHelper::get_numeric_arg('fid');
$sid   = CinisisHttpHelper::get_textual_arg('sid');

// Draw the document.
$display = new CinisisDisplayHelper('Subfield finder');
$form    = $display->formInputText('entry', $entry);
$form   .= $display->formInputText('fid', $fid);
$form   .= $display->formInputText('sid', $sid);
$display->form($form, basename(__FILE__));

// Get a db instance.
$isis = new IsisFinder();

// Setup database and entry number.
if ($isis) {
  // Query database.
  $field                = $isis->getFieldArray($fid);
  $subfield             = $isis->getSubfieldName($fid, $sid);
  list($entry, $result) = $isis->nextSubfield($field, $subfield, $entry);

  // Navigation bar.
  $display->navbar($entry, $isis->entries, 'subfield.php', '&fid='. $fid . '&sid='. $sid);

  // Format output.
  echo "<pre>\n";
  echo "Selected field: $fid: ". $field['name'] .".\n";
  echo "Selected subfield: $sid: $subfield.\n";
  echo "Showing entry ". $display->entryLink($entry) ." from ". $isis->entries ." total entries.\n";
  echo "Repetitions found: ". count($result[$field['name']]) .".\n";
  echo "\n";
  print_r($result[$field['name']]);
  echo '</pre>';
}

$display->footer();
