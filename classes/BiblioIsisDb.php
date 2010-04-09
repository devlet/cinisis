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
   * Class instance of a perl interpreter;
   */
  var $perl;

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
    // Save db schema.
    $this->format = $schema;

    // Setup $fdt.
    foreach ($schema['fields'] as $field => $info) {
      $this->fdt[$field] = $info['name'];
    }

    // Create a perl instance.
    $this->perl = new Perl();
  }

  /**
   * Send requests to the perl backend.
   *
   * @param $method
   *   Backend method name to invoke.
   *
   * @param $args
   *   Backend method arguments.
   *
   * @return
   *   Backend return value.
   */
  function backend($method = 'count', $args = NULL) {
    // Setup the database.
    $name = $this->format['db']['name'];
    $db   = CinisisDb::file("$name/$name", 'db');

    // Setup arguments.
    if ($args != NULL) {
      $args = '('. $args .')';
    }

    try {
      // Call backend.
      return $this->perl->eval('
        use Biblio::Isis;

        my $isis = new Biblio::Isis(
           isisdb => "'. $db .'",
        );

        return $isis->'. $method . $args .';');
    }
    catch (PerlException $exception) {
      echo __CLASS__ .': Perl error: ' . $exception->getMessage();
      return FALSE;
    }    
  }

  /**
   * Read an entry.
   *
   * @see IsisDb::read()
   */  
  public function read($id) {
    // Database query.
    $results = $this->backend('to_hash', $id);

    // Tag results.
    $data = $this->tag($results);

    // Return the result.
    return $data;
  }

  /**
   * Return number of rows in the database.
   *
   * @see IsisDb::read()
   */  
  public function rows() {
    return $this->backend('count');
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
   * to names.
   *
   * @param $results
   *   Database query results.
   *
   * @return
   *   Tagged database result.
   */
  function tag($results) {
    foreach ($results as $key => $value) {
      // Key '000' used to hold MFN.
      if ($key != '000') {
        $name        = $this->format['fields'][$key]['name'];
        $data[$name] = $this->repetition($key, $value);

        // Subfield handling.
        if (isset($this->format['fields'][$key]['subfields']) && is_array($data[$name])) {
          foreach ($data[$name] as $subkey => $subvalue) {
            $subname               = $this->format['fields'][$key]['subfields'][$subkey];
            $data[$name][$subname] = $subvalue;
            unset($data[$name][$subkey]);
          }
        }
      }
    }

    return $data;    
  }

  /**
   * Deals with repetition.
   *
   * As Biblio::Isis always return field values as arrays, we
   * have to check the database schema to see if we have to
   * convert then to a single value.
   *
   * @param $field
   *   Database field.
   *
   * @param $value
   *   Query field result.
   *
   * @return
   *   The value according to the repetition config.
   */
  function repetition($field, $value) {
    if (isset($this->format['fields'][$field]['repeat']) &&
      $this->format['fields'][$key]['repeat'] == FALSE   && is_array($value)) {
        return $value[0];
      }
    return $value;
  }
}
