<?php

/**
 * CinisisDb main class.
 */
class CinisisDb {
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
  function __construct($config = 'config/config.yaml') {
    try {
      // Check main configuration.
      $config = $this->parse($config);

      // Check database schema.
      $schema = $this->parse('schemas/'. $config['database'] .'.yaml', 'schema');
    } catch (Exception $e) {
      echo '[cinisis] caught exception: ',  $e->getMessage(), "\n";
      return FALSE;
    }

    // Setup database connection.
    $this->implementation = $config['implementation'] .'Db';
    $this->db             = new $this->implementation($schema);
  }

  /**
   * Config file load.
   *
   * @param $file
   *   Config file.
   *
   * @return
   *   Array with configuration.
   */
  function load($file) {
    if (!file_exists($file)) {
      throw new Exception('Config '. $file .' not found.');
    }

    // Load configuration.
    return Spyc::YAMLLoad($file);
  }

  /**
   * Parse configuration.
   *
   * @param $config
   *   Config file or array with configuration.
   *
   * @param $type
   *   Configuration type (either 'cinisis' or 'schema').
   *
   * @return
   *   Array with configuration or FALSE on error.
   */
  function parse($config, $type = 'cinisis') {
    // Load configuration if needed.
    if (!is_array($config)) {
      $config = $this->load($config);
    }

    // Check configuration.
    $class = ucfirst($type) .'Db';
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
  public function check($config) {
    // Set default database backend if needed.
    if (!isset($config['implementation'])) {
      $config['implementation'] = 'PhpIsis';
    }

    // Check database configuration.
    if (!isset($config['database'])) {
      throw new Exception('No database set on configuration.');
    }

    return $config;
  }
}
