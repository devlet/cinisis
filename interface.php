<?php

/**
 * Generic interface for reading Isis databases.
 */
interface IsisDb {
  /**
   * Constructor.
   *
   * The implementation constructor should accept a database
   * schema definition and setup the appropriate db resource.
   *
   * @param $schema
   *   High level database schema description.
   *
   * @return
   *   Database resource or FALSE in case of error.
   *
   * @see default_schema()
   */
  public function __construct($schema);

  /**
   * Read an entry from the database.
   *
   * @param $id
   *   Database row id.
   */
  public function read($id);

  /**
   * Return number of rows in the database.
   *
   * @return
   *   Number of rows in the database.
   */
  public function rows();

  /**
   * Return a default example schema.
   *
   * The example schema should have all information the implementation
   * needs to be able to open and read a database.
   *
   * @return
   *   Array with a sample database schema.
   */
  public function default_schema();
}
