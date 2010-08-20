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
  $result = $isis->run();

  // Format output.
  $display->pre(print_r($result));
}

$display->footer();
