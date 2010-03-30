<?php

/**
 * PHP-Isis implementation of IsisDb.
 */
class PhpIsisDb implements IsisDb {
  var $db;
  var $format;

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

  public function read($id) {
    $results = isis_search('$', $this->db);
    if (!isis_data_seek($results, $id)) {
      return FALSE;
    }

    return $this->tag(isis_fetch_array($results));
  }

  public function rows() {
    return isis_last_mfn($this->db);
  }

  public function default_schema() {
    return SchemaDb::default_schema();
  }

  // Tag results of a db query.
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
