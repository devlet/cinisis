<?php

class SchemaDb {
  /**
   * Schema format example.
   */
  public function default_schema() {
    $schema = array(
      'db'             => array(
        'name'         => 'dbname',
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

    return $schema;
  }
}