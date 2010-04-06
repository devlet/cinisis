<?php

/**
 * Malete implementation of IsisDb.
 */
class MaleteDb implements IsisDb {
  /**
   * @var $fdt
   *   Field description table.
   */
  var $fdt;

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

    // Open a database connection.
    $this->db = new Isis_Db($this->fdt, $schema['db']['name'], new Isis_Server());
    if (!$this->db->srv->sock) {
      return FALSE;
    }
  }

  /**
   * Read an entry.
   *
   * @see IsisDb::read()
   *
   * @todo
   *   Subfields.
   */  
  public function read($id) {
    if (!is_numeric($id)) {
      return FALSE;
    }
    if ($results !== FALSE) {
      $results = $this->db->read($id);
      return $this->tag($results);
    }
    else {
      return FALSE;
    }
  }

  /**
   * Return number of rows in the database.
   *
   * The Malete API doen't implement such feature so we
   * have to emulate it by iterating over all entries
   * until MaleteDb::read() returns FALSE.
   *
   * @see IsisDb::read()
   */  
  public function rows() {
    $id = 0;
    while($this->db->read($id)) {
      $id++; 
    }
    return $id;
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
    return SchemaDb::check($schema, $section);
  }

  /**
   * Tag results of a db query.
   *
   * This function converts the keys of query result from field numbers
   * to names and and also puts repetition fields into place as Malete
   * deals with field repetition by using a 'tag' property in the resulting
   * query object. 
   *
   * @param $results
   *   Database query results.
   *
   * @return
   *   Tagged database result.
   */
  function tag($results) {
    foreach ($results->val as $key => $value) {
      $field = $results->tag[$key];
      $name  = $this->format['fields'][$field]['name'];

      // Handles field repetition.
      if ($this->format['fields'][$field]['repeat']) {
        $data[$name][] = $value;
      }
      else {
        $data[$name] = $value;
      }
    }
    return $data;
  }
}
