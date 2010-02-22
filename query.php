<?php
/**
 * Query script.
 */

// Import Malete Library
require 'malete/php/Isis.php';

?>

<html><head><title>Query</title></head><body>

<?php
	
  // Get request parameters a and b
  // (as well as any plain numeric and v%d style
  $param = Isis_Http::fromReq(array(
			'a' => 22,
			'b' => 42,
			));

  // Create a db with field list ("fdt")
  $fdt = array(
		  'Periodico'            =>  1,
		  'Data'                 =>  2,
		  'Titulo'               =>  3,
		  'Autor'                =>  4,
		  'Assuntos primarios'   =>  5,
		  'Assuntos secundarios' =>  6,
		  'Ilustrado'            =>  7,
		  'Caderno'              =>  8,
		  'Pagina'               =>  9,
		  'Arquivo digital'      => 10,
		  'Forma documento'      => 11,
		  'Local de publicacao'  => 12,
		  'Observacoes'          => 13,
		  'Descritores imagem'   => 14,
		  'Termo geografico'     => 16,
		  'Coluna'               => 17,
		  'Recorte'              => 19,
		  'Alimentador'          => 20,
		  'Tema Anuario'         => 21,
	      );
?>

<h2>server</h2>

<?php
  $db = new Isis_Db($fdt, 'anu10', new Isis_Server());
  if (!$db->srv->sock) {
    echo "could not contact server\n";
  }
  else {
?>

<h3>terms beginning with...</h3>

<?php
    $query = 'a';
    $terms = $db->terms($query);
    echo "got ",count($terms), " terms for '$query'</br>\n";
    foreach ($terms as $t) {
      list($cnt, $term) = explode("\t", $t);
      echo "'$term'($cnt) ";
    }
    echo "</br>\n";
?>

<h3>query reading records</h3>

<?php
    $query = 'Corumbiara';
    $recs  = $db->query($query);
    echo "got ",count($recs), " records for '$query'</br>\n";
    foreach ($recs as $r) {
      echo "<pre>---\n", $r->toString(), "---\n</pre>\n";
    }
?>

<h3>query reading 1</h3>

<?php
    $r = $db->read(1);
    echo "<pre>---\n", $r->toString(), "---\n</pre>\n";
  } // end else could contact server
?>

</body></html>
