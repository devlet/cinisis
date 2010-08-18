<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Get entry number.
$entry = CinisisHttpHelper::get_numeric_arg('entry');

// Draw the document.
$display = new CinisisDisplayHelper('Isis Navigator');
$display->form($display->form_input_text('entry', $entry));

// Get a db instance.
$isis = new CinisisDb();

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
  echo "<pre>\n";
  echo "Showing entry $entry from $entries total entries.\n";
  echo "\n";
  print_r($result);
  echo '</pre>';
}

$display->footer();
