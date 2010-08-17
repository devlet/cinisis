<?php

/**
 * Http helper for test scripts.
 */
class CinisisHttpHelper {
  /**
   * Get an argument.
   *
   * @param $name
   *   Argument name.
   *
   * @param $mixed
   *   Default value.
   *
   * @return
   *   Argument value.
   */
  static function get_arg($name, $default = 1) {
    // Get the query parameter.
    if (isset($_GET[$name]) && ! empty($_GET[$name])) {
      $arg = $_GET[$name];
    }
    else {
      $arg = $default;
    }  

    return $arg;
  }

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
    return (int) self::get_arg($name, 1);
  }

  /**
   * Get a string argument.
   *
   * @param $name
   *   Argument name.
   *
   * @return
   *   Argument value.
   */
  static function get_textual_arg($name) {
    return (string) self::get_arg($name, 'a');
  }
}
