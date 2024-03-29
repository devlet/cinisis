<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Get entry number.
$entry = CinisisHttpHelper::getNumericArg('entry');

// Draw the document.
$display = new CinisisDisplayHelper('Cinisis Navigator');
$display->form($display->formInputText('entry', $entry));

// Get a db instance.
$isis = new Cinisis();

// Setup database and entry number.
if ($isis->db) {
  // Get the number of entries.
  $entries = $isis->db->entries();

  // Input sanitization.
  if ($entries < $entry) {
    $entry = 1;
  }

  // Query database.
  $result  = $isis->db->read($entry);
  $display->navbar($entry, $entries);

  // Format output.
  $display->pre("Showing entry $entry from $entries total entries.");
  $display->dump($result);
}

$display->footer();
