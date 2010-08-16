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
    $this->header();
    $this->title($title);
  }

  /**
   * Draws a page title.
   *
   * @param $title
   *   Page title;
   */
  function title($title) {
    echo "<h1>$title</h1>\n";
  }

  /**
   * Draws the page header.
   */
  function header() {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    echo '</head>';
    echo '<body>';
  }

  /**
   * Draws the page footer.
   */
  function footer() {
    echo '</body>';
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
  function form($content, $action = 'index.php', $method = 'get') {
    echo '<form action="'. $action .'" method="'. $method .'">';
    echo $content;
    echo '<input type="submit" />';
    echo '</form>';
    echo '<br />';
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
  function form_input_text($name, $default = null) {
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
  function navbar($entry, $entries, $action = 'index.php', $extra = NULL) {
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
}
