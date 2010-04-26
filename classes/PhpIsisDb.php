<?php

/**
 * PHP-Isis implementation of IsisDb.
 */
class PhpIsisDb implements IsisDb {
  /**
   * @var $db
   *   Database resource.
   */  
  var $db;

  /**
   * @var $format
   *   Database format, derived from $schema.
   */
  var $format;

  /**
   * Constructor.
   *
   * @see IsisDb::__construct()
   */
  public function __construct($schema) {
    // Save db schema.
    $this->format = $schema;

    // Setup $fdt.
    foreach ($schema['fields'] as $field => $info) {
      $this->fdt[$field] = $info['name'];
    }

    // Open the database.
    $name     = $schema['db']['name'];
    $this->db = isis_open(CinisisDb::file("$name/$name", 'db'));
  }

  /**
   * Read an entry.
   *
   * The PHP-Isis API doen't implement such feature so we
   * have to emulate it by geting all entries and using
   * isis_data_seek() to get the desired record.
   *
   * @see IsisDb::read()
   *
   * @todo
   *   Subfields.
   */    
  public function read($id) {
    $results = isis_search('$', $this->db);
    if (!isis_data_seek($results, $id-1)) {
      return FALSE;
    }

    // Tag results.
    $data = $this->tag(isis_fetch_array($results));

    // Charset conversion.
    array_walk_recursive($data, array(__CLASS__, 'charset'));

    // Return the result.
    return $data;
  }

  /**
   * Return number of rows in the database.
   *
   * @see IsisDb::rows()
   */    
  public function rows() {
    return isis_last_mfn($this->db);
  }

  /**
   * Return an example schema.
   *
   * @see IsisDb::example()
   */    
  public function example() {
    return SchemaDb::example();
  }

  /**
   * Check configuration.
   *
   * @see IsisDb::check()
   */  
  public function check($schema, $section = NULL) {
    // Check API availability.
    if (!function_exists('isis_open')) {
      throw new Exception('Could not find function isis_open. Please check your php-isis installation.');
      return FALSE;
    }

    // Check schema configuration.
    return SchemaDb::check($schema, $section);
  }

  /**
   * Tag results of a db query.
   *
   * This function converts the keys of query result from field
   * numbers to names.
   *   
   * @param $results
   *   Database query results.
   *
   * @return
   *   Tagged database result.
   */
  function tag($results) {
    foreach ($results as $key => $value) {
      if ($key != 'mfn') {
        $name        = $this->format['fields'][$key]['name'];
        $data[$name] = $value;
      }
    }

    return $data;
  }

  /**
   * Charset conversion.
   *
   * Converts a string from the database charset to UTF-8.
   *
   * @param $data
   *   String to be converted.
   *
   * @return
   *   String converted to UTF-8.
   */
  function charset($data) {
    return iconv($data, $this->format['db']['charset'], 'UTF-8');
  }
}
