<?php
/*! \mainpage Cinisis Database Reader
 *
 * Cinisis is a <a href="https://secure.wikimedia.org/wikipedia/en/wiki/CDS/ISIS">CDS/ISIS</a>
 * database reading library written in PHP. It's intended for integrating or migrating
 * existing ISIS databases into other applications. It is a wrapper around other ISIS
 * libraries and tools, providing an uniform interface and iterators for easily fetching
 * data without bothering with internals.
 *
 * Cinisis works with the following ISIS backend libraries:
 *
 *   - <a href="http://search.cpan.org/~dpavlin/Biblio-Isis-0.24/lib/Biblio/Isis.pm">Biblio::Isis</a> through <a href="http://pecl.php.net/package/perl">Perl PECL extension</a>, which is the recommended choice.
 *   - <a href="http://malete.org">GNI's Malete</a>.
 *   - <a href="http://pecl.php.net/package/isis">Openisis</a>.
 *
 * Both Malete and Openisis support is outdated in favour os Biblio::Isis as it has proven
 * to be simpler and more functional.
 *
 * <h2>Installation and usage</h2>
 *
 * \verbinclude README.txt
 *
 * <h2>Example</h2>
 *
 * The following exemple shows how to read a database entry using
 * two different ISIS backends:
 *
 * \include samples/read.php
 */

// Autoloader.
function cinisis_autoload($class) {
  $base = dirname(__FILE__) .'/';

  if (strstr($class, 'Db')) {
    $file = 'classes/backends/'. $class .'.php';
  }
  elseif (strstr($class, 'Iterator')) {
    $file = 'classes/iterators/'. $class .'.php';
  }
  elseif (strstr($class, 'Helper')) {
    $file = 'classes/helpers/'. $class .'.php';
  }
  else {
    $file = 'classes/'. $class .'.php';
  }

  if (file_exists($base . $file)) {
    require_once $base . $file;
  }
}

// Register autoloader.
spl_autoload_register("cinisis_autoload");

?>
