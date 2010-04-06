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

      // Set database implementation.
      $this->implementation = $config['implementation'] .'Db';

      // Check database schema.
      $schema = $this->parse('schemas/'. $config['database'] .'.yaml', $this->implementation);
    } catch (Exception $e) {
      echo __CLASS__ .' caught exception: ',  $e->getMessage(), "\n";
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
    return Spyc::YAMLLoad($file);
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
  public function check($config) {
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
}
