<?php

/**
 * CinIsis main class.
 */
class CinIsis {
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
   * @param $file
   *   Optional parameter to set alternative config file.
   */   
  function __construct($file = 'config/config.yaml') {
    try {
      // Load main configuration.
      $config = $this->config($file);

      // Load database schema.
      $schema = $this->config('schemas/'. $config['database'] .'.yaml');
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
  function config($file) {
    if (!file_exists($file)) {
      throw new Exception('Config '. $file .' not found.');
    }

    // Load configuration.
    return Spyc::YAMLLoad($file);
  }
}
