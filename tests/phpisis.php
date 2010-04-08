<?php
/**
 * Test query script.
 */
?>

<h3>Testing pecl-isis</h3>
<?php

  $db = isis_open('db/anu10/anu10');
  //$db = isis_open('db/tupi/tupi');
  print_r(isis_last_mfn($db));

  echo '<table>';

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
  echo '</table>';
?>

</body></html>
