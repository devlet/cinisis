<?php

class PerlSingleton {
  private static $instance = null;

  private function __construct() {
    $this->perl = new Perl();
  }

  public static function getInstance() {
    if(self::$instance == null) {
      self::$instance = new self;
    }

    return self::$instance->perl;
  }  
}
