<?php

/**
 * Provides basic Isis read capabilities around Cinisis.
 */
class IsisReader {
  /**
   * Constructor.
   */ 
  public function __construct($config = null) {
    return $this->open($config);
  }

  /**
   * Open a database.
   *
   * @param $config
   *   Config file or array.
   */
  public function open($config) {
    $this->isis = new Cinisis($config);

    if ($this->isis->db) {
      $this->entries = $this->isis->db->entries();
      $this->format  = $this->isis->db->format;
      $this->fields  = $this->format['fields'];
    }
    else {
      return FALSE;
    }
  }

  /**
   * Alias to $isis->db->read().
   *
   * @param $entry
   *   Row number.
   *
   * @return
   *   Resulting data.
   */
  public function read($entry) {
    // Always store the last result.
    $this->result = $this->isis->db->read($entry);

    // Return the result.
    return $this->result;
  }

  /**
   * Remove brackets from strings whithin an array.
   *
   * @param $value
   *   Array with bracketed strings.
   */
  public function removeBrackets($value) {
    $value = str_replace('<', '', $value);
    $value = str_replace('>', '', $value);
    return $value;
  }

  /**
   * Remove brackets from strings whithin an array.
   *
   * @param &$values
   *   Array with bracketed strings.
   */
  public function removeBracketsFromArray(&$values) {
    foreach ($values as $key => $value) {
      $values[$key] = $this->removeBrackets($value);
    }
  }

  /**
   * Explode a bracketed string into values. Just strings
   * inside brackets are returned.
   *
   * @param $subject
   *   Strings containing brackets.
   *
   * @return
   *   Array of matched strings.
   */
  public function explodeBrackets($subject) {
    preg_match_all('/<[^<>]*>/', $subject, $values);
    return $this->filterBrackets($values[0]);
  }

  /**
   * Filter out brackets from strings.
   *
   * @param $values
   *   String (or array filled with strings) to be filtered.
   *
   * @result
   *   Filtered string or array.
   */
  public function filterBrackets($values) {
    if (is_array($values)) {
      foreach ($values as $key => $value) {
        $values[$key] = $this->filterBrackets($value);
      }
    }
    else {
      $values = preg_replace(array('/</', '/>/'), '', $values);
    }

    return $values;
  }

  /**
   * Check if a string has brackets.
   *
   * @param $value
   *   String to be compared.
   *
   * @return
   *   True if string has brackets, false otherwise.
   */
  public function hasBrackets($value) {
    if (strstr($value, '<') && strstr($value, '>')) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Explode values from fields or subfields. Split values
   * inside brackets if needed, but then doesn't return any
   * value outside brackets.
   *
   * @param $value
   *   String with values.
   *
   * @return
   *   Array with values.
   */
  public function explodeValue($value) {
    if ($this->hasBrackets($value)) {
      return $this->explodeBrackets($value);
    }
    else {
      if (!is_array($value)) {
        $value = array($value);
      }
    }

    return $value;
  }

  /**
   * Whether to join field and subfields in a single array.
   *
   * @return
   *   Boolean.
   */
  public function joinSubfields() {
    if (Cinisis::join_subfields($this->format)) {
      return TRUE;
    }

    return FALSE;
  }
}
