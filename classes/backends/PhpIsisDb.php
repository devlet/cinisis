<?php

/**
 * PHP-Isis implementation of IsisDb.
 *
 * @warning
 *   This implementation is currently outdated and lacks
 *   basic functionalities such as subfield handling and
 *   therefore it's use is not recommended.
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
   * @var $log
   *   Class action log.
   */
  var $log;

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
    $this->db = isis_open(Cinisis::file("$name/$name", 'db'));
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
   *   Subfield handling.
   */    
  public function read($id) {
    $results = isis_search('$', $this->db);
    if (!isis_data_seek($results, $id - 1)) {
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
   * Return number of entries in the database.
   *
   * @see IsisDb::entries()
   */    
  public function entries() {
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
  static function check($schema, $section = NULL) {
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
  function charset(&$data) {
    $data = iconv($this->format['db']['charset'], 'UTF-8', $data);
  }  

  /**
   * Class logger.
   *
   * @param $message
   *   Log message.
   */
  function logger($message) {
    $this->log[] = $message;
  }
}
