<?php

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

  public function rows() {
    $id = 1;
    while($this->db->read($id)) {
      $id++; 
    }
    return $id - 1;
  }

  public function default_schema() {
    return SchemaDb::default_schema();
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
