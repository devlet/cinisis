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

    if ($results) {
      // Tag results.
      $data = $this->tag($results);

      // Charset conversion.
      if (is_array($data)) {
        array_walk_recursive($data, array(__CLASS__, 'charset'));
      }

      // Return the result.
      return $data;
    }
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
  static function check($schema, $section = NULL) {
    // Check API availability.
    if (!class_exists('Perl')) {
      throw new Exception('Could not find Perl class. Please check your php-perl installation.');
      return FALSE;
    }

    // Check schema configuration.
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
   *
   * @todo
   *    Alternative handling for when $key is not set.
   *
   * @fixme
   *    Repetitive fields are not being tagged.
   */
  function tag($results) {
    foreach ($results as $key => $value) {
      // Key '000' used to hold MFN.
      if ($key != '000') {
        if (!isset($this->format['fields'][$key])) {
          continue;
        }

        // Format, repetition and subfield handling.
        $name        = $this->format['fields'][$key]['name'];
        $data[$name] = $this->repetition($key, $value);
        $data[$name] = $this->subfields($data[$name], $key);
      }
    }

    return $data;
  }

  function has_subfields($key) {
    if (isset($this->format['fields'][$key]['subfields'])) {
      return TRUE;
    }

    return FALSE;
  }

  function subfields_switch($key, &$value) {
    foreach ($value as $subkey => $subvalue) {
      if (isset($this->format['fields'][$key]['subfields'][$subkey])) {
        $subname = $this->format['fields'][$key]['subfields'][$subkey];
      } else {
        $subname = $subkey;
      }

      $value[$subname] = $subvalue;

      if ($subkey != $subname) {
        unset($value[$subkey]);
      }
    }
  }

  function subfields($name, $key) {
    if ($this->has_subfields($key) && is_array($name)) {
      if ($this->is_repetitive($key, $name)) {
        foreach ($name as $entry => $value) {
          $this->subfields_switch($key, $value);
          $name[$entry] = $value;
        }
      }
      else {
        $this->subfields_switch($key, $name);
      }
    }

    return $name;
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
   * @return
   *   True if repetitive, false otherwise.
   *
   * @todo
   *   Warn on configuration/data mismatch ("config says that
   *   $field is non repetitive but data shows a repetition").
   */
  function is_repetitive($field, $value) {
    if (isset($this->format['fields'][$field]['repeat']) &&
      $this->format['fields'][$field]['repeat'] == FALSE && is_array($value)) {
        return FALSE;
      }

    return TRUE;
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
  function repetition($key, $value) {
    if ($this->is_repetitive($key, $value)) {
      return $value;
    }
    else {
      return $value[0];
    }
  }

  /**
   * Charset conversion.
   *
   * Converts a string from the database charset to UTF-8.
   *
   * @param $data
   *   String to be converted.
   *
   * @return
   *   String converted to UTF-8.
   */
  function charset(&$data) {
    $data = iconv($this->format['db']['charset'], 'UTF-8', $data);
  }
}
