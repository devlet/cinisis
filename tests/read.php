<?php
/**
 * Cinisis - Isis db reading tool.
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=cp850" />
  </head>
  <body>

<table><tr>

<?php

// Import Malete Library.
require_once '../index.php';

$configs = array(
  0 => array(
    'implementation' => 'PhpIsis',
    'database'       => 'anu10',
    ),
  1 => array(
    'implementation' => 'Malete',
    'database'       => 'anu10',
    ),
);

foreach ($configs as $config) {
  // Get a db instance.
  $isis = new CinisisDb($config);

  // Test connection.
  if ($isis->db) {
    $result = $isis->db->read(1);
    $rows   = $isis->db->rows();

    // Format output.
    echo '<td>';
    echo '<pre>';
    echo 'Implementation: '. $config['implementation'] ."\n";
    echo "Rows: $rows\n";
    print_r($result);
    echo '</pre>';
    echo '</td>';
  }
}

?>

</tr></td></table>
</body>
