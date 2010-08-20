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
   * @param $default
   *   Default value.
   *
   * @return
   *   Argument value.
   */
  static function getArg($name, $default = 1) {
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
  static function getNumericArg($name) {
    return (int) self::getArg($name, 1);
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
  static function getTextualArg($name) {
    return htmlspecialchars((string) self::getArg($name, 'a'));
  }
}
