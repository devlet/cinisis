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
   * @param $config
   *   Alternative config file (defaults to 'config/config.yaml').
   *
   * @todo
   *   Config check.
   */   
  function __construct($config = NULL) {
    if ($config == NULL) {
      $config = 'config/config.yaml';
    }

    // Load configuration.
    $config = Spyc::YAMLLoad($config);

    // Load database schema.
    $schema = Spyc::YAMLLoad('schemas/'. $config['database'] .'.yaml');

    // Setup database connection.
    $this->implementation = $config['implementation'] .'Db';
    $this->db             = new $this->implementation($schema);
  }
}
