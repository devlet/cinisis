<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Get input data.
$entry = CinisisHttpHelper::getNumericArg('entry');
$fid   = CinisisHttpHelper::getNumericArg('fid');
$sid   = CinisisHttpHelper::getTextualArg('sid');

// Draw the document.
$display = new CinisisDisplayHelper('Subfield finder');
$form    = $display->formInputText('entry', $entry);
$form   .= $display->formInputText('fid', $fid);
$form   .= $display->formInputText('sid', $sid);
$script  = basename(__FILE__);
$display->form($form, $script);

// Get a db instance.
$isis = new IsisFinder();

// Setup database and entry number.
if ($isis) {
  // Query database.
  $field                = $isis->getFieldArray($fid);
  $subfield             = $isis->getSubfieldName($fid, $sid);
  list($entry, $result) = $isis->nextSubfield($field, $subfield, $entry);

  // Navigation bar.
  $display->navbar($entry, $isis->entries, $script, '&fid='. $fid . '&sid='. $sid);

  // Format output.
  $display->pre("Selected field: $fid: ". $field['name'] .".");
  $display->pre("Selected subfield: $sid: $subfield.");
  $display->pre("Showing entry ". $display->entryLink($entry) ." from ". $isis->entries ." total entries.");
  $display->pre("Repetitions found: ". count($result[$field['name']]) .".");
  $display->dump($result[$field['name']]);
}

$display->footer();
