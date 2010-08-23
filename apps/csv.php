<?php
/**
 * Cinisis - Isis db reading tool.
 */

// Import Cinisis Library.
require_once '../index.php';

// Get a db instance.
$isis    = new IsisConnector();
$display = new CinisisDisplayHelper(NULL);

// Test connection.
if ($isis) {
  // Setup format, response header and CSV title.
  $format = $isis->format;
  $display->httpHeader('application/text/x-csv', 'export.csv');
  $display->csvTitles($format);
  $display->csvRow();

  // Format output.
  foreach(new IsisEntryIterator($isis) as $entry => $result) {
    // Filter results.
    array_walk_recursive($result, array($isis, 'removeBracketsCallback'));

    foreach ($format['fields'] as $field) {
      $display->mergeCsvItems($isis->getMainItems($field));

      if (isset($field['subfields']) && is_array($field['subfields'])) {
        foreach ($field['subfields'] as $subfield) {
          $display->mergeCsvItems($isis->getSubfields($field, $subfield));
        }
      }
    }

    $display->csvRow();
  }
}
