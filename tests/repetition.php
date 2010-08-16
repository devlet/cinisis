<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Draw the document.
$display = new CinisisDisplayHelper('Repetition finder');
$form    = $display->form_input_text('code') . $display->form_input_text('display');
$display->form($form, 'repetition.php');

// Get field code.
$code = CinisisHttpHelper::get_numeric_arg('code');

// Get a db instance.
$isis = new CinisisDb();

// Setup database and entry number.
if ($isis->db) {
  // Get the number of entries.
  $field   = $isis->db->format['fields'][$code]['name'];
  $entries = $isis->db->entries();
  $entry   = CinisisHttpHelper::get_numeric_arg('entry') - 1;

  // Query database.
  do {
    $result = $isis->db->read(++$entry);
    if ($entry == $entries) {
      break;
    }
  } while (!isset($result[$field]) || count($result[$field]) < 2);

  // Navigation bar.
  $display->navbar($entry, $entries, $repetition, '&code='. $code);

  // Format output.
  echo "<pre>\n";
  echo "Showing entry $entry from $entries total entries.\n";
  echo "Selected field: $field.\n";
  echo "Repetitions found: ". count($result[$field]) ."\n";
  echo "\n";
  print_r($result);
  echo '</pre>';
}

$display->footer();
?>
