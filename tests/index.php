<?php
/**
 * Cinisis - Isis db reading tool.
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>

<form action="index.php" method="get"> 
Entry: <input name="entry" type="text" /> 
<input type="submit" />
</form>
<br />

<?php

// Import Cinisis Library.
require_once '../index.php';

// Get the query parameter.
if (isset($_GET["entry"]) && ! empty($_GET["entry"])) {
  $entry = (int) $_GET["entry"];
}
else {
  $entry = 1;
}

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

  // First / prev links.
  if ($entry != 1) {
    $prev = $entry - 1;
    echo '<a href="index.php?entry=1">first</a> ';
    echo '<a href="index.php?entry='. $prev .'">&lt; prev</a> ';
  }

  // Next / last links.
  if ($entry < $entries) {
    $next = $entry + 1;
    echo '<a href="index.php?entry='. $next .'">next &gt;</a> ';
    echo '<a href="index.php?entry='. $entries .'">last</a>';
  }

  // Format output.
  echo "<pre>\n";
  echo "Showing entry $entry from $entries total entries.\n";
  echo "\n";
  print_r($result);
  echo '</pre>';
}

?>
</body>
