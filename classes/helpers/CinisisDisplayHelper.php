<?php

class CinisisDisplayHelper {
  function __construct($title) {
    $this->header();
    $this->title($title);
  }

  function title($title) {
    echo "<h1>$title</h1>\n";
  }

  function header() {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    echo '</head>';
    echo '<body>';
  }

  function footer() {
    echo '</body>';
  }

  function form($content, $action = 'index.php', $method = 'get') {
    echo '<form action="'. $action .'" method="'. $method .'">';
    echo $content;
    echo '<input type="submit" />';
    echo '</form>';
    echo '<br />';
  }

  function form_input_text($name) {
    return ucfirst($name) .': <input name="'. $name .'" type="text" />';
  } 

  function navbar($entry, $entries, $action = 'index.php') {
    // First / prev links.
    if ($entry != 1) {
      $prev = $entry - 1;
      echo '<a href="'. $action .'?entry=1">first</a> ';
      echo '<a href="'. $action .'?entry='. $prev .'">&lt; prev</a> ';
    }

    // Next / last links.
    if ($entry < $entries) {
      $next = $entry + 1;
      echo '<a href="'. $action .'?entry='. $next .'">next &gt;</a> ';
      echo '<a href="'. $action .'?entry='. $entries .'">last</a>';
    }
  }
}
