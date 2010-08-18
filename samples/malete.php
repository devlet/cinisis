<?php
/**
 * Malete test script.
 */

// Import requisites.
require_once '../index.php';
require_once 'contrib/malete/php/Isis.php';

// Draw the document.
$display = new CinisisDisplayHelper('Malete Test');
	
// Create a db with field list ("fdt")
$fdt = array(
  'Test field 1' =>  1,
  'Test field 2' =>  2,
  'Test field 3' =>  3,
);

// Database connection setup.
$db  = 'dbname';
$db  = new Isis_Db($fdt, $db, new Isis_Server());

$display->h2('Server');

if (!$db->srv->sock) {
  echo "could not contact server\n";
}
else {
  $display->h3("Number of records");
  $query = 'Test';
  $recs  = $db->num_recs($query);
  echo "Got ". count($recs) ." terms for '$query'</br>\n";

  $display->h3("Terms beginning with...");
  $terms = $db->terms(strtoupper($query));
  echo "Got ". count($terms) ." terms for '$query'</br>\n";

  foreach ($terms as $t) {
    list($cnt, $term) = explode("\t", $t);
    echo "'$term'($cnt) \n";
  }

  $display->br();
  $display->h3('Query reading records');
  $recs = $db->query(strtoupper($query));

  echo "Got ". count($recs) ." records for '$query'</br>\n";

  foreach ($recs as $r) {
    $display->pre($r->toString());
  }

  $display->h3('Query reading a record');
  $r = $db->read(6);
  $display->pre($r->toString());
}

$display->footer();
