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

// Get a db instance.
$isis = new IsisFinder();

if ($isis) {
  // Draw the document.
  $display = new CinisisDisplayHelper('Text finder');
  $form    = $display->formInputText('entry', $entry);
  $form   .= $display->radios('fid', $isis->getFieldNames(), $fid);
  $form   .= $display->radios('sid', $isis->getSubFieldNames($fid), $sid);
  $form   .= $display->formInputText('text', $text);
  $script  = basename(__FILE__);
  $display->form($form, $script);

  // Query database.
  $field = $isis->getFieldArray($fid);
  $item  = ($sid == 'main') ? 'main' : $isis->getSubfieldName($fid, $sid, TRUE);
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
