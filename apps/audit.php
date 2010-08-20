<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import requisites.
require_once '../index.php';

// Draw the document.
$display = new CinisisDisplayHelper('Database audit');

// Get a db instance.
$isis = new IsisAudit();

// Setup database and entry number.
if ($isis) {
  // Run audit.
  $isis->run();

  // Display log messages.
  foreach ($isis->log as $message) {
    $display->pre(print_r($message));
  }
}

$display->footer();
