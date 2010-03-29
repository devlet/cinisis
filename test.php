<?php
/**
 * Test query script.
 */

// Import Malete Library
require 'contrib/malete/php/Isis.php';

// Import Spyc
include('contrib/spyc/spyc.php');

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
  $fdt_anu10 = array(
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

  $fdt_tupi = array(
		  'cod.titulo'                  => 1,
		  'postopo'                     => 2,
		  'num.entrada'                 => 3,
		  'num.tombo'                   => 4,
		  'datain'                      => 5,
		  'dataex'                      => 6,
		  'acervo (DIF ou PRE)'         => 7,
		  'cadarq'                      => 10,
		  'caddep'                      => 11,
		  'cadddl'                      => 12,
		  'outarq'                      => 13,
		  'categorias'                  => 15,
		  'presok'                      => 16,
		  'roteiro de locução'          => 17,
		  'mat.acervo'                  => 18,
		  'mat.orig'                    => 19,
		  'titulo'                      => 20,
		  'outros titulos'              => 22,
		  'data/série'                  => 24,
		  'conteudo/sinopse'            => 25,
		  'descritores'                 => 26,
		  'descritores secundários'     => 27,
		  'identidades'                 => 28,
		  'ndxlib'                      => 29,
		  'materiais'                   => 40,
		  'materiais'                   => 41,
		  'materiais'                   => 42,
		  'materiais'                   => 43,
		  'materiais'                   => 44,
		  'materiais'                   => 45,
		  'mat'                         => 46,
		  'mat'                         => 47,
		  'mat'                         => 48,
		  'mat'                         => 49,
		  'mat'                         => 50,
		  'mat'                         => 51,
		  'mat'                         => 52,
		  'mat'                         => 53,
		  'mat'                         => 54,
		  'mat'                         => 55,
		  'evol.estado tec.'            => 56,
		  'movimentacao'                => 57,
		  'movimentacao'                => 58,
		  'obsmat'                      => 59,
		  'producao*'                   => 60,
		  'dir.arte*'                   => 61,
		  'fotografia*'                 => 62,
		  'musica*'                     => 63,
		  'som*'                        => 64,
		  'montagem*'                   => 65,
		  'direcao*'                    => 66,
		  'arg/roteiro*'                => 67,
		  'distribuicao*'               => 68,
		  'colab./outros'               => 69,
		  'producao1'                   => 70,
		  'dir.arte1'                   => 71,
		  'fotografia1'                 => 72,
		  'musica1'                     => 73,
		  'som1'                        => 74,
		  'montagem1'                   => 75,
		  'producao2(res.p/expandir)'   => 80,
		  'dir.arte2(res.p/expandir)'   => 81,
		  'fotografia2(res.p/expandir)' => 82,
		  'musica2(res.p/expandir)'     => 83,
		  'som2(res.p/expandir)'        => 84,
		  'loc.prod.lan'                => 85,
		  'dat.prod.lan'                => 86,
		  'lab/est/locacoes'            => 87,
		  'premios'                     => 88,
		  'fontes'                      => 89,
		  'certificados'                => 95,
		  'examinador'                  => 98,
		  'observações'                 => 99,
		  'termos geográficos'          => 165,
		  'quicktime'                   => 900,
		  'revisão'                     => 901,
		  'vídeo'                       => 902,
    );

  //$db   = 'tupi';
  $db   = 'anu10';
  $fdt  = ${'fdt_'. $db};
  $yaml = Spyc::YAMLDump($fdt);
?>

<h2>server</h2>

<?php
  $db = new Isis_Db($fdt, $db, new Isis_Server());
  if (!$db->srv->sock) {
    echo "could not contact server\n";
  }
  else {
    //print_r($db);
?>

<h3>number of records...</h3>

<?php
    $query = 'HORA';
    $recs  = $db->num_recs($query);
    echo "got ",count($recs), " terms for '$query'</br>\n";
?>

<h3>terms beginning with...</h3>

<?php
    $query = 'Hora';
    $terms = $db->terms(strtoupper($query));
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
    $recs  = $db->query(strtoupper($query));
    echo "got ",count($recs), " records for '$query'</br>\n";
    foreach ($recs as $r) {
      echo "<pre>---\n", $r->toString(), "---\n</pre>\n";
    }
?>

<h3>query reading a record</h3>

<?php
    $r = $db->read(6);
    echo "<pre>---\n", $r->toString(), "---\n</pre><br>\n";
    echo '<pre>';
    //print_r($r);
    //print_r($r->val[8]);
    //print_r(preg_split('/\t/', $r->val[7]));
    //print_r(array_flip($fdt_anu10));
    echo '</pre>';
  } // end else could contact server
?>
</body></html>
