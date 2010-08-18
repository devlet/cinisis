<?php

/**
 * SchemaDb class with standard database procedures and
 * configuration.
 */
class SchemaDb {
  /**
   * Return the required database config.
   *
   * @return
   *   Array with required config.
   */
  static function required() {
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
   * Return an example database schema.
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
   * Recursively check for required fields in a database schema.
   *
   * @see IsisDb::check()
   */
  static function check($schema, $section = NULL) {
    if ($section === NULL) {
      $section = SchemaDb::required();
    }

    foreach ($section as $key => $value) {
      if (!isset($schema[$key])) {
        throw new Exception('Undefined required parameter '. $key .' on database configuration.');
        return FALSE;
      }

      if (is_array($value)) {
        if (SchemaDb::check($schema[$key], $section[$key]) == FALSE) {
          return FALSE;
        }
      }
    }

    return $schema;
  }
}
