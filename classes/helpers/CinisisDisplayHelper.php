<?php

/**
 * Display helpers for test scripts.
 */
class CinisisDisplayHelper {
  /**
   * Constructor.
   *
   * @param $title
   *   Page title;
   */
  function __construct($title) {
    $this->header($title);
    $this->title($title);
  }

  /**
   * Draws a page title.
   *
   * @param $title
   *   Page title;
   */
  static function title($title) {
    if (php_sapi_name() == "cli") {
      echo "$title\n";
    }
    else {
      echo "<h1>$title</h1>\n";
    }
  }

  /**
   * Draws the page header.
   *
   * @param $title
   *   Page title;
   */
  static function header($title) {
    if (php_sapi_name() == "cli") {
      echo "$title\n";
    }
    else {
      echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
      echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
      echo '<head>';
      echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
      echo '<title>'. $title .'</title>';
      echo '</head>';
      echo '<body>';
    }
  }

  /**
   * Draws the page footer.
   */
  static function footer() {
    if (php_sapi_name() != "cli") {
      echo '</body>';
    }
  }

  /**
   * Draws a form.
   *
   * @param $content
   *   Form inner content.
   *
   * @param $action
   *   Form action.
   *
   * @param $method
   *   Form method.
   */
  static function form($content, $action = 'index.php', $method = 'get') {
    if (php_sapi_name() != "cli") {
      echo '<form action="'. $action .'" method="'. $method .'">';
      echo $content;
      echo '<input type="submit" />';
      echo '</form>';
      echo '<br />';
    }
  }

  /**
   * Draws a form text input.
   *
   * @param $name
   *   Input name.
   *
   * @param $default
   *   Default value.
   *
   * @return
   *   Rendered text input.
   */
  static function form_input_text($name, $default = null) {
    if (php_sapi_name() == "cli") {
      return;
    }

    if ($default) {
      $default = 'value="'. $default .'"';
    }

    return ucfirst($name) .': <input name="'. $name .'" type="text" '. $default .'/>';
  } 

  /**
   * Draws a navigation bar.
   *
   * @param $entry
   *   Current entry.
   *
   * @param $entries
   *   Total number of entries.
   *
   * @param $action
   *   Page action.
   *
   * @param $extra
   *   Extra parameters.
   */
  static function navbar($entry, $entries, $action = 'index.php', $extra = NULL) {
    if (php_sapi_name() == "cli") {
      return;
    }

    // First / prev links.
    if ($entry != 1) {
      $prev = $entry - 1;
      echo '<a href="'. $action .'?entry=1"'. $extra .'>first</a> ';
      echo '<a href="'. $action .'?entry='. $prev . $extra .'">&lt; prev</a> ';
    }

    // Next / last links.
    if ($entry < $entries) {
      $next = $entry + 1;
      echo '<a href="'. $action .'?entry='. $next . $extra .'">next &gt;</a> ';
      echo '<a href="'. $action .'?entry='. $entries . $extra .'">last</a>';
    }
  }

  /**
   * Format a link.
   *
   * @param $action
   *   Link action.
   *
   * @param $args
   *   Action arguments.
   *
   * @param $title
   *   Link title.
   *
   * @return
   *   Formatted link.
   */
  static function link($action, $args, $title) {
    if (php_sapi_name() != "cli") {
      return '<a href="'. $action . $args .'">'. $title .'</a>';
    }
  }

  /**
   * Format an entry link.
   *
   * @param $entry
   *   Entry number.
   *
   * @return
   *   Formatted link.
   */
  static function entry_link($entry) {
    if (php_sapi_name() != "cli") {
      return self::link('index.php', '?entry='. $entry, $entry);
    }
  }

  /**
   * Draws tags for opening a table.
   */
  static function open_table() {
    if (php_sapi_name() != "cli") {
      echo '<table><tr>';
    }
  }

  /**
   * Draws tags for closing a table.
   */
  static function close_table() {
    if (php_sapi_name() != "cli") {
      echo '</tr></table>';
    }
  }

  /**
   * Draws a h2 element.
   *
   * @param $text
   *   Inner text.
   */
  static function h2($text) {
    if (php_sapi_name() == "cli") {
      echo "$text\n";
    }
    else {
      echo "<h2>$text</h2>";
    }
  }

  /**
   * Draws a h3 element.
   *
   * @param $text
   *   Inner text.
   */
  static function h3($text) {
    if (php_sapi_name() == "cli") {
      echo "$text\n";
    }
    else {
      echo "<h3>$text</h3>";
    }
  }

  /**
   * Draws a line break element.
   */
  static function br() {
    if (php_sapi_name() == "cli") {
      echo "\n";
    }
    else {
      echo "<br />";
    }
  }

  /**
   * Draws a pre format block element.
   *
   * @param $text
   *   Inner text.
   */
  static function pre($text) {
    if (php_sapi_name() == "cli") {
      echo "$text\n";
    }
    else {
      echo "<pre>\n$text</pre>";
    }
  }  
}
