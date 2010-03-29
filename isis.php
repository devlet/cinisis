<?php
/**
 * Database procedures.
 */

/**
 * Generic interface for reading Isis databases.
 */
interface IsisDb {
  // Constructor.
  public function __construct($schema);

  // Return field data for a given entry.
  public function fields($id = NULL);

  // Return subfield data for a given entry.
  public function subfields($id = NULL);

  // Read an entry.
  public function read($id);

  // Return number of rows in the database.
  public function rows();

  // Return a default example schema.
  public function default_schema();
}

/**
 * Malete implementation of IsisDb.
 */
class MaleteDb implements IsisDb {
  var $fdt;
  var $db;
  var $format;

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

  public function fields($id = NULL) {
  }

  public function subfields($id = NULL) {
  }

  public function read($id) {
    if (!is_numeric($id)) {
      return FALSE;
    }
    $results = $this->db->read($id);
    return $this->tag($results);
  }

  public function rows() {
  }

  /**
   * Schema format example.
   */
  public function default_schema() {
    $schema = array(
      'db'             => array(
        'name'         => 'dbname',
      ),
      'fields'         => array(
        1              => array(
          'name'       => 'field_name',
          'size'       => 1000,
          'format'     => 'numeric',
          'repeat'     => TRUE,
          'subfields'  => array(
            'a'        => 'test',
            'b'        => 'test2',
          ),
        ),
      ),
    );

    return $schema;
  }

  // Tag results of a db query.
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
