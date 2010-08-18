<?php

/**
 * Malete implementation of IsisDb.
 *
 * @warning
 *   This implementation is currently outdated and lacks
 *   basic functionalities such as subfield handling and
 *   therefore it's use is not recommended.
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
   *   Subfield handling.
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
   * Return number of entries in the database.
   *
   * The Malete API doen't implement such feature so we
   * have to emulate it by iterating over all entries
   * until MaleteDb::read() returns FALSE.
   *
   * @see IsisDb::entries()
   */  
  public function entries() {
    // The first entry in a malete database has id 1 and
    // not 0, therefore $id's initial value should be 1.
    $id = 1;
    while($this->db->read($id)) {
      $id++; 
    }
    return $id - 1;
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
    if (!class_exists('Isis_Db')) {
      throw new Exception('Could not find Isis_Db class. Please check your malete installation.');
      return FALSE;
    }

    // Check schema configuration.
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
