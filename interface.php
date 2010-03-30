<?php

/**
 * Generic interface for reading Isis databases.
 */
interface IsisDb {
  /**
   * Constructor.
   *
   * @param $schema
   *   High level database schema description.
   *
   * @see default_schema()
   */
  public function __construct($schema);

  /**
   * Read an entry.
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
   * @return
   *   Array with a sample database schema.
   */
  public function default_schema();
}
