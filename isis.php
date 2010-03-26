<?php
/**
 * Database procedures.
 */

/**
 * Schema format example.
 */
$schema = array(
  'db'          => array(
    'name'      => 'dbname',
  ),
  'fields'      => array(
    'field_name'  => array(
      'id'        => 1,
      'size'      => 1000,
      'format'    => 'numeric',
      'repeat'    => TRUE,
      'subfields' => array(
        'a'       => 'test',
        'b'       => 'test2',
      ),
    ),
  ),
);

/**
 * Generic interface for reading Isis databases.
 */
interface IsisDb {
  // Constructor.
  public function __construct($schema);

  // Return field data for a given entry.
  public function fields($id);

  // Return subfield data for a given entry.
  public function subfields($id);

  // Read an entry.
  public function read($id);

  // Return number of rows in the database.
  public function rows();
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
    $format = $schema;

    // Setup $fdt used by malete.
    foreach ($schema['fields'] as $field => $info) {
      $fdt[$field] = $info['id'];
    }

    // Open a database connection.
    $db = new Isis_Db($fdt, $schema['db']['name'], new Isis_Server());
  }

  public function fields($id == NULL) {
  }

  public function subfields($id == NULL) {
  }

  // TODO: put result into $schema format.
  public function read($id) {
    if (!is_numeric($id) {
      return FALSE;
    }
    return $this->$db->read($id);
  }

  public function rows() {
  }
}

?>
