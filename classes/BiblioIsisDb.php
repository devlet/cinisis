<?php

/**
 * Biblio::Isis implementation of IsisDb.
 */
class BiblioIsisDb implements IsisDb {
  /**
   * @var $fdt
   *   Field description table.
   */
  var $fdt;

  /**
   * @var $db
   *   Database resource.
   */
  var $db;

  /**
   * @var $format
   *   Database format, derived from $schema.
   */
  var $format;

  /**
   * Constructor.
   *
   * @see IsisDb::__construct()
   */ 
  public function __construct($schema) {
  }

  /**
   * Read an entry.
   *
   * @see IsisDb::read()
   *
   * @todo
   *   Subfields.
   */  
  public function read($id) {
  }

  /**
   * Return number of rows in the database.
   *
   * The Malete API doen't implement such feature so we
   * have to emulate it by iterating over all entries
   * until MaleteDb::read() returns FALSE.
   *
   * @see IsisDb::read()
   */  
  public function rows() {
  }

  /**
   * Return an example schema.
   *
   * @see IsisDb::example()
   */  
  public function example() {
    return SchemaDb::example();
  }

  /**
   * Check configuration.
   *
   * @see IsisDb::check()
   */  
  public function check($schema, $section = NULL) {
    return SchemaDb::check($schema, $section);
  }

  /**
   * Tag results of a db query.
   *
   * This function converts the keys of query result from field numbers
   * to names and and also puts repetition fields into place as Malete
   * deals with field repetition by using a 'tag' property in the resulting
   * query object. 
   *
   * @param $results
   *   Database query results.
   *
   * @return
   *   Tagged database result.
   */
  function tag($results) {
  }
}
