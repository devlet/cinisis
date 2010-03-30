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

  // Read an entry.
  public function read($id);

  // Return number of rows in the database.
  public function rows();

  // Return a default example schema.
  public function default_schema();
}
