<?php
/**
 * Php Isis test script.
 */

// Import requisites.
require_once '../index.php';

// Draw the document.
$display = new CinisisDisplayHelper('Php Isis Test');

// Open the database.
$db = isis_open('db/dbname/dbname');
print_r(isis_last_mfn($db));

$display->open_table();

$result = isis_search('$', $db);
while ($record = isis_fetch_flat_array($result) ) {
  print (" <tr><td colspan=\"2\">MFN: $record[mfn]</td></tr>\n");
  for ($i=0; $i<count($record)-1; ++$i) { //-1 porque el mfn es +1
    list ($tag, $value) = $record[$i];
    print (" <tr>\n".
      " <td>$tag</td>\n".
      " <td>".htmlspecialchars($value)."</td>\n".
      " </tr>\n");
  }
}

$display->close_table();
$display->footer();
