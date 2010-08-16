<?php
/**
 * Cinisis - Isis db reading tool.
 */
?>

<?php
// Import requisites.
require_once '../index.php';

// Draw the document.
$display = new CinisisDisplayHelper('Isis Reader');
?>

<table><tr>

<?php

$configs = array(
  0 => array(
    'implementation' => 'PhpIsis',
    'database'       => 'anu10',
    ),
  1 => array(
    'implementation' => 'BiblioIsis',
    'database'       => 'anu10',
    ),
);

foreach ($configs as $config) {
  // Get a db instance.
  $isis = new CinisisDb($config);

  // Test connection.
  if ($isis->db) {
    $result  = $isis->db->read(1);
    $entries = $isis->db->entries();

    // Format output.
    echo '<td>';
    echo '<pre>';
    echo 'Implementation: '. $config['implementation'] ."\n";
    echo "Rows: $entries\n";
    print_r($result);
    echo '</pre>';
    echo '</td>';
  }
}

?>

</tr></td></table>
</body>
