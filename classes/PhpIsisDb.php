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

    // Setup $fdt used by malete.
    foreach ($schema['fields'] as $field => $info) {
      $this->fdt[$field] = $info['name'];
    }

    // Open the database.
    $name     = $schema['db']['name'];
    $this->db = isis_open("db/$name/$name");
  }

  /**
   * Read an entry.
   *
   * @see IsisDb::read()
   */    
  public function read($id) {
    $results = isis_search('$', $this->db);
    if (!isis_data_seek($results, $id)) {
      return FALSE;
    }

    return $this->tag(isis_fetch_array($results));
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
   * Return a default example schema.
   *
   * @see IsisDb::default_schema()
   */    
  public function default_schema() {
    return SchemaDb::default_schema();
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
        $name  = $this->format['fields'][$key]['name'];
        $data[$name] = $value;
      }
    }
    return $data;
  }  
}
