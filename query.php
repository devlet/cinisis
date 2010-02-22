<?php // vim:syntax=php
/*
	The Malete project - the Z39.2/Z39.50 database framework of OpenIsis.
	Version 0.9.x (patchlevel see file Version)
	Copyright (C) 2001-2004 by Erik Grziwotz, erik@openisis.org

	This library is free software; you can redistribute it and/or
	modify it under the terms of the GNU Lesser General Public
	License as published by the Free Software Foundation; either
	version 2.1 of the License, or (at your option) any later version.

	This library is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public
	License along with this library; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	see README for more information
EOH */

// $Id: demo.php,v 1.3 2004/11/02 13:44:42 kripke Exp $
// demo for the Isis package

require 'malete/php/Isis.php'; // use require 'Isis/Rec.php' if you need only this
?><html><head>
	<title>Demo for package Isis</title>
</head><body>
<?php
	
	// get request parameters a and b
	// (as well as any plain numeric and v%d style
	$param = Isis_Http::fromReq( array(
		'a' => 22, 'b' => 42
	) );

	// create a db with field list ("fdt")
	$fdt = array(
		'periodico' => 1,
		'data'      => 2,
		'titulo'    => 3,
	);
?>

<h2>server</h2>
<?php
	$db = new Isis_Db($fdt, 'anu10', new Isis_Server());
	if ( !$db->srv->sock )
		echo "could not contact server\n";
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
	foreach ($recs as $r)
		echo "<pre>---\n", $r->toString(), "---\n</pre>\n";
?>

	<h3>query reading 1</h3>
<?php
	$r = $db->read(1);
	echo "<pre>---\n", $r->toString(), "---\n</pre>\n";
?>
<?php
	} // end else could contact server
?>
</body></html>
