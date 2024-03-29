<?php

/**
 * Cinisis main class.
 */
class Cinisis {
  /**
   * @var $db
   *   Database resource.
   */  
  var $db;

  /**
   * @var $implementation
   *   Database implementation.
   */
  var $implementation;

  /**
   * Constructor.
   *
   * @param $config
   *   Optional parameter to set alternative config file or array
   *   with configuration.
   */   
  function __construct($config = NULL) {
    $this->open($config);
  }

  /**
   * Open an ISIS database.
   *
   * @param $config
   *   Optional parameter to set alternative config file or array
   *   with configuration.
   */
  public function open($config)
  {
    try {
      // Check main configuration.
      $config = $this->parse($this->file($config));

      // Set database implementation.
      $this->implementation = $config['implementation'] .'Db';

      // Check database schema.
      $schema = $this->parse($this->file($config['database'] .'.yaml', 'schemas'), $this->implementation);
    } catch (Exception $e) {
      echo __CLASS__ .' caught exception: ', $e->getMessage(), "\n";
      return FALSE;
    }

    // Setup database connection.
    $this->db = new $this->implementation($schema);
  }

  /**
   * Config file load.
   *
   * @param $file
   *   Config file.
   *
   * @return
   *   Array with configuration or FALSE if error.
   */
  public function load($file) {
    if (!file_exists($file)) {
      throw new Exception('Config '. $file .' not found.');
      return FALSE;
    }

    // Load configuration.
    return $this->yaml($file);
  }

  /**
   * Load YAML into array using backend libraries.
   *
   * @param $file
   *   Config file.
   *
   * @return
   *   Array with configuration or FALSE if error.
   */
  static function yaml($file) {
    if (file_exists('../contrib/spyc/spyc.php')) {
      // Use Spyc.
      include_once '../contrib/spyc/spyc.php';
      return Spyc::YAMLLoad($file);
    }
    elseif (is_callable(array('sfYaml', 'load'))) {
      // Use symfony built-in yaml loader.
      return sfYaml::load($file);
    }

    throw new Exception('No suitable methods for parsing YAML found.');
  }

  /**
   * Parse configuration.
   *
   * @param $config
   *   Config file or array with configuration.
   *
   * @param $class
   *   Configuration class name.
   *
   * @return
   *   Array with configuration or FALSE on error.
   */
  public function parse($config, $class = __CLASS__) {
    // Load configuration if needed.
    if (!is_array($config)) {
      $config = $this->load($config);
    }

    // Check configuration.
    return call_user_func(array($class, 'check'), $config);
  }

  /**
   * Check configuration.
   *
   * @param $config
   *   Config file or array with configuration.
   *
   * @return
   *   Array with configuration or FALSE on error.
   */  
  static function check($config) {
    // Set default database backend if needed.
    if (!isset($config['implementation'])) {
      $config['implementation'] = 'PhpIsis';
    }

    // Check database configuration.
    if (!isset($config['database'])) {
      throw new Exception('No database set on configuration.');
      return FALSE;
    }

    return $config;
  }

  /**
   * Get library base folder.
   *
   * @return
   *   Return base folder.
   */
  static function base() {
    return dirname(__FILE__) .'/../';
  }

  /**
   * Get a file path.
   *
   * @param $config
   *   Config file name (either relative to the library or absolute)
   *   or array with configuration.
   *
   * @param $section
   *   Config file section (ignored for absolute files).
   *
   * @return
   *   Return the assembled file path.
   */
  static function file($config = NULL, $section = 'config') {
    // Check config format (array, NULL or relative config path).
    if (is_array($config)) {
      return $config;
    }
    elseif ($config == NULL) {
      $config = "$section/config.yaml";
    }
    elseif (substr($config, 0, 1) != '/') {
      $config = "$section/$config";
    }

    return call_user_func(array(__CLASS__, 'base')) .'/'. $config;
  }

  /**
   * Whether to join field and subfields in a single array.
   *
   * @param $format
   *   Database format.
   *
   * @return
   *   Boolean.
   */
  static function join_subfields($format) {
    if (isset($format['db']['join_subfields']) && $format['db']['join_subfields']) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Determine the main field name depending on db configuration.
   *
   * @param $key
   *   Field key.
   *
   * @param $format
   *   Database format.
   *
   * @return
   *   Main field name, 'field' by default.
   */
  static function main_field_name($format, $key) {
    if (self::join_subfields($format)) {
      return $format['fields'][$key]['name'];
    }

    return 'field';
  }
}
