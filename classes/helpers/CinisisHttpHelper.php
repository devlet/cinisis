<?php

/**
 * Http helper for test scripts.
 */
class CinisisHttpHelper {
  /**
   * Get a numeric argument.
   *
   * @param $name
   *   Argument name.
   *
   * @return
   *   Argument value.
   */
  static function get_numeric_arg($name) {
    // Get the query parameter.
    if (isset($_GET[$name]) && ! empty($_GET[$name])) {
      $arg = (int) $_GET[$name];
    }
    else {
      $arg = 1;
    }  

    return $arg;
  }
}
