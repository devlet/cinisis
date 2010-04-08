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

<?php

// Import Malete Library.
require_once '../index.php';

// Get a db instance.
$isis = new CinisisDb();

// Test connection.
if ($isis->db) {
  $result = $isis->db->read(1);
  $rows   = $isis->db->rows();

  // Format output.
  echo '<pre>';
  echo "Rows: $rows\n";
  print_r($result);
  echo '</pre>';
}

?>
</body>
