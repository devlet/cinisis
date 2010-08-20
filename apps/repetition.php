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
$display = new CinisisDisplayHelper('Repetition finder');
$form    = $display->formInputText('entry', $entry);
$form   .= $display->formInputText('fid', $fid);
$script  = basename(__FILE__);
$display->form($form, $script);

// Get a db instance.
$isis = new IsisFinder();

// Setup database and entry number.
if ($isis) {
  // Query database.
  $field                = $isis->getFieldArray($fid);
  list($entry, $result) = $isis->nextRepetition($field, $entry);

  // Navigation bar.
  $display->navbar($entry, $isis->entries, $script, '&fid='. $fid);

  // Format output.
  $display->pre("Selected field: $fid: ". $field['name'] .".");
  $display->pre("Showing entry ". $display->entryLink($entry) ." from ". $isis->entries ." total entries.");
  $display->pre("Repetitions found: ". count($result[$field['name']]) .".");
  $display->dump($result[$field['name']]);
}

$display->footer();
