<?php

class SchemaDb {
  /**
   * Return the required database config.
   *
   * @return
   *   Array with required config.
   */
  public function required() {
    $schema = array(
      'db'             => array(
        'name'         => 'dbname',
      ),
      'fields'         => array(
      ),
    );

    return $schema;
  }

  /**
   * Return the optional database config.
   *
   * @return
   *   Array with optional config.
   */  
  public function optional() {
    $schema   = array(
      'db'             => array(
        'charset'      => 'charset',
      ),
    );

    return $schema;
  }

  /**
   * Return an example schema.
   *
   * @see IsisDb::example()
   */    
  public function example() {
    $required = SchemaDb::required();
    $optional = SchemaDb::optional();
    $schema   = array(
      'db'             => array(
        'charset'      => 'charset',
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

    return array_merge_recursive($required, $optional, $schema);
  }

  /**
   * Check required fields.
   *
   * @todo
   */
  function check($schema = NULL) {
    return $schema;
  }
}
