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
    if ($title != NULL) {
      $this->header($title);
      $this->title($title);
    }
  }

  /**
   * Determine internal method names.
   *
   * @param $method
   *   Method name.
   *
   * @return
   *   Method name.
   */
  static function methodName($method) {
    if (php_sapi_name() == "cli") {
      return 'cli'. ucfirst($method);
    }
    else {
      return 'web'. ucfirst($method);
    }
  }

  /**
   * Dispatcher, dynamic version.
   *
   * @param $method
   *   Method name.
   *
   * @param $arguments
   *   Argument list.
   *
   * @return
   *   Callback result.
   */
  public function __call($method, $arguments) {
    $method = $this->methodName($method);

    if (method_exists($this, $method)) {
      return call_user_func_array(array($this, $method), $arguments);
    }
  }

  /**
   * Dispatcher, static version.
   *
   * @param $method
   *   Method name.
   *
   * @param $arguments
   *   Argument list.
   *
   * @return
   *   Callback result.
   */
  public static function __callStatic($method, $arguments) {
    $method = self::methodName($method);

    if (is_callable('self', $method)) {
      return call_user_func_array(array('self', $method), $arguments);
    }
  }

  /**
   * Draws a page title.
   *
   * @param $title
   *   Page title;
   */
  protected static function webTitle($title) {
    if (php_sapi_name() == "cli") {
      echo "$title\n";
    }
    else {
      echo "<h1>$title</h1>\n";
    }
  }

  /**
   * Draws title, CLI version.
   *
   * @param $title
   *   Page title;
   */
  protected static function cliTitle($title) {
    echo "$title\n";
  }

  /**
   * Draws the page header.
   *
   * @param $title
   *   Page title;
   */
  protected static function webHeader($title) {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    echo '<title>'. $title .'</title>';
    echo '</head>';
    echo '<body>';
  }

  /**
   * Draws the page footer.
   */
  protected static function webFooter() {
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
  protected static function webForm($content, $action = 'index.php', $method = 'get') {
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
  protected static function webFormInputText($name, $default = null) {
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
  protected static function webNavbar($entry, $entries, $action = 'index.php', $extra = NULL) {
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
  protected static function webLink($action, $args, $title) {
    return '<a href="'. $action . $args .'">'. $title .'</a>';
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
  protected static function webEntryLink($entry) {
    return self::link('index.php', '?entry='. $entry, $entry);
  }

  /**
   * Format a link to the field app.
   *
   * @param $entry
   *   Entry number.
   *
   * @param $fid
   *   Field code.
   *
   * @return
   *   Formatted link.
   */
  protected static function webFieldLink($entry, $fid) {
    return "Field search: ". self::link('field.php', '?entry='. $entry .'&fid='. $fid, $entry);
  }

  /**
   * Format a link to the field app.
   *
   * @param $entry
   *   Entry number.
   *
   * @param $fid
   *   Field code.
   *
   * @return
   *   Formatted link.
   */
  protected static function webRepetitionLink($entry, $fid) {
    return "Repetition search: ". self::link('repetition.php', '?entry='. $entry .'&fid='. $fid, $entry);
  }

  /**
   * Draws tags for opening a table.
   */
  protected static function webOpenTable() {
    echo '<table><tr>';
  }

  /**
   * Draws tags for closing a table.
   */
  protected static function webCloseTable() {
    echo '</tr></table>';
  }

  /**
   * Draws a h2 element.
   *
   * @param $text
   *   Inner text.
   */
  protected static function webH2($text) {
    echo "<h2>$text</h2>";
  }

  /**
   * Draws a h2 element, CLI version.
   *
   * @param $text
   *   Inner text.
   */
  protected static function cliH2($text) {
    echo "$text\n";
  }

  /**
   * Draws a h3 element.
   *
   * @param $text
   *   Inner text.
   */
  protected static function webH3($text) {
    echo "<h3>$text</h3>";
  }

  /**
   * Draws a h3 element, CLI version.
   *
   * @param $text
   *   Inner text.
   */
  protected static function cliH3($text) {
    echo "$text\n";
  }

  /**
   * Draws a line break element.
   */
  protected static function webBr() {
    echo "<br />";
  }

  /**
   * Draws a line break element, CLI version.
   */
  protected static function cliBr() {
    echo "\n";
  }

  /**
   * Draws a pre format block element.
   *
   * @param $text
   *   Inner text.
   */
  protected static function webPre($text) {
    echo "<pre>$text</pre>";
  }

  /**
   * Draws a pre open element.
   */
  protected static function webPreOpen() {
    echo "<pre>";
  }

  /**
   * Draws a pre open element.
   */
  protected static function webPreClose() {
    echo "</pre>";
  }

  /**
   * Draws a pre format block element.
   *
   * @param $text
   *   Inner text.
   */
  protected static function cliPre($text) {
    echo "$text\n";
  }

  /**
   * Dump value.
   *
   * @param $var
   *   Variable to dump.
   */
  protected static function webDump($var) {
    self::preOpen();
    print_r($var);
    self::preClose();
  }

  /**
   * Dump value.
   *
   * @param $var
   *   Variable to dump.
   */
  protected static function cliDump($var) {
    print_r($var);
  }

  /**
   * Set the response helper.
   *
   * @param $mime
   *   MIME type.
   *
   * @param $filename
   *   File name.
   */
  protected static function webHttpHeader($mime, $filename) {
    header("Content-type: $mime");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");
  }

  /**
   * Display a value with CSV format.
   *
   * @param $value
   *   Value entry.
   */
  protected static function webCsv($value = NULL) {
    echo '"'. preg_replace('/"/', '""', $value) .'",';
  }

  /**
   * Display CSV titles.
   *
   * @param $format
   *   ISIS database format.
   */
  protected static function webCsvTitles($format) {
    // Format fields.
    foreach ($format['fields'] as $field) {
      self::csv($field['name']);
      if (isset($field['subfields']) && is_array($field['subfields'])) {
        foreach ($field['subfields'] as $key => $value) {
          self::csv($field['name'] .': '. $value);
        }
      }
    }
  }

  /**
   * Display a new CSV row.
   */
  protected static function webCsvRow() {
    echo "\n";
  }
  
  /**
   * Merge items in a CSV roll.
   *
   * @param $items
   *   Array with items to be merged.
   */
  protected static function webMergeCsvItems($items) {
    if (!empty($items)) {
      self::csv(implode(';', $items));
    }
    else {
      self::csv();
    }
  }

  /**
   * Renders a radio button.
   *
   * @param $name
   *   Radio name.
   *
   * @param $value
   *   Radio value.
   *
   * @param $caption
   *   Radio caption.
   *
   * @param $checked
   *   Whether the radio is checked.
   *
   * @return
   *   HTML rendered radio button.
   */
  protected static function webRadio($name, $value, $caption, $checked = NULL) {
    return '<input type="radio" name="'. $name .'" value="'. $value .'" '. $checked .' > '. $value .' - '. $caption .'<br />';
  }

  /**
   * Draws a combination of radio buttons.
   *
   * @param $name
   *   Radio names.
   *
   * @param $data
   *   Array with values and captions.
   *
   * @param $checked
   *   Index of the selected option.
   */
  protected static function webRadios($name, $data, $checked = NULL) {
    $radios = '<table><tr><td>';
    $count  = 0;

    foreach ($data as $key => $value) {
      if ($count++ > 5) {
        $count   = 0;
        $radios .= '</td><td>';
      }

      if ($key == $checked) {
        $radios .= self::webRadio($name, $key, $value, 'checked');
      }
      else {
        $radios .= self::webRadio($name, $key, $value);
      }
    }

    $radios .= '</td></tr></table>';

    return $radios;
  }
}
