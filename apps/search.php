<?php
/**
 * Cinisis - Isis db reading tool.
 *
 * @todo
 *   Checkbox for $match.
 */

// Import requisites.
require_once '../index.php';

// Get input data.
$entry = CinisisHttpHelper::getNumericArg('entry');
$fid   = CinisisHttpHelper::getNumericArg('fid');
$sid   = CinisisHttpHelper::getTextualArg('sid');
$text  = CinisisHttpHelper::getTextualArg('text');

// Draw the document.
$display = new CinisisDisplayHelper('Text finder');
$form    = $display->formInputText('entry', $entry);
$form   .= $display->formInputText('fid', $fid);
$form   .= $display->formInputText('sid', $sid);
$form   .= $display->formInputText('text', $text);
$script  = basename(__FILE__);
$display->form($form, $script);

// Get a db instance.
$isis = new IsisFinder();

// Setup database and entry number.
if ($isis) {
  // Query database.
  $field = $isis->getFieldArray($fid);
  $item  = ($sid == 'main') ? 'main' : $isis->getSubfieldName($fid, $sid);
  list($entry, $result) = $isis->nextResult($field, $item, $text, $entry);

  // Navigation bar.
  $display->navbar($entry, $isis->entries, $script, '&fid='. $fid . '&sid='. $sid . '&text='. $text);

  // Format output.
  $display->pre("Selected field: $fid: ". $field['name'] .".");
  $display->pre("Selected item: $sid: $item.");
  $display->pre("Showing entry ". $display->entryLink($entry) ." from ". $isis->entries ." total entries.");
  $display->pre("Repetitions found: ". count($result[$field['name']]) .".");
  $display->dump($result[$field['name']]);
}

$display->footer();
