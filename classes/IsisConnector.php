<?php

/**
 * IsisConnector: provides an easy interface to connect an
 * application with Cinisis.
 */
class IsisConnector {
  /**
   * Constructor.
   */ 
  public function __construct() {
    $this->isis = new CinisisDb();

    if ($this->isis->db) {
      $this->rows   = $this->isis->db->rows();
      $this->format = $this->isis->db->format;
      $this->fields = $this->format['fields'];
    }
    else {
      return FALSE;
    }
  }

  /**
   * Alias to $isis->db->read().
   *
   * @param $row
   *   Row number.
   *
   * @return
   *   Resulting data.
   */
  public function read($row) {
    // Always store the last result.
    $this->result = $this->isis->db->read($row);

    // Return the result.
    return $this->result;
  }

  /**
   * Get all values of a given field.
   *
   * @param $field
   *   Field array.
   *
   * @return
   *   Field data.
   */
  public function getFields($field) {
    if (isset($this->result[$field['name']])) {
      return $this->result[$field['name']];
    }

    return array();
  }

  /**
   * Get the value of a given field.
   *
   * @param $field
   *   Field array.
   *
   * @param $row
   *   Optional row number if repetitive field.
   *
   * @return
   *   Field data.
   */
  public function getField($field, $row = 0) {
    if (isset($this->result[$field['name']][$row]['field'])) {
      return $this->result[$field['name']][$row]['field'];
    }
  }

  /**
   * Get the value of a given subfield.
   *
   * @param $field
   *   Field array.
   *
   * @param $subfield
   *   Subfield name.
   *
   * @param $row
   *   Row number if repetitive data.
   *
   * @return
   *   Subfield data.
   */
  public function getSubfield($field, $subfield, $row = 0) {
    if (isset($this->result[$field['name']][$row]['subfields'][$subfield])) {
      return $this->result[$field['name']][$row]['subfields'][$subfield];
    }
  }

  /**
   * Get the list of subfields from a given field.
   *
   * @param $field
   *   Field array.
   */
  public function getSubfields($field) {
    if (isset($field['subfields'])) {
      return $field['subfields'];
    }
    return array();
  }

  /**
   * Determine which model field an ISIS db field should be mapped to.
   * When importing an ISIS database to another system, a mapping
   * provided in the database schema can be used to put the originating
   * entries (fields and subfields) in the right place at the destination
   * database.
   *
   * Map format:
   *
   *   map:
   *     type: relation
   *  
   *   map:
   *     type: value
   *     field: dest
   *     subfields:
   *       a: dest
   *       b: dest
   *
   * Examples:
   *
   *   map:
   *     type: Performer
   *  
   *   map:
   *     type: value
   *     field: title
   *     subfields:
   *       a: subtitle
   * 
   * @param $field
   *   Field array.
   *
   * @param $subfield
   *   Subfield name. 
   *
   * @return
   *   A map destination to the field or subfield.
   * 
   * @todo
   *   Convert field and subfield names to valid field names.
   */
  public function getMap($field, $subfield = NULL) {
    if ($subfield == NULL) {
      if (isset($field['map']['field'])) {
        // Custom map provided.
        $dest = 'set'. ucfirst($field['map']['field']);
      }
      else {
        // Default map.
        $dest = 'set'. ucfirst($field['name']);
      }
    }
    else {
      $key = $this->getSubfieldKey($field, $subfield);

      if (isset($field['map']['subfields'][$key])) {
        // Custom map provided.
        $dest = 'set'. ucfirst($field['map']['subfields'][$key]);
      }
      else {
        // Default map.
        $dest = 'set'. ucfirst($subfield);
      }
    }

    return $dest;
  }

  /**
   * Get the mapping type of a given field.
   *
   * @param $field
   *   Field array.
   *
   * @return
   *   The mapping type.
   */
  public function getMapType($field) {
    return isset($field['map']['type']) ? $field['map']['type'] : FALSE;
  }

  /**
   * Check on an ISIS schema whether a field has a map.
   *
   * @param $field
   *   Field array.
   *
   * @return
   *   TRUE if field has a map, FALSE otherwise.
   */
  public function fieldHasMap($field) {
    if (isset($field['map']['field'])) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check on an ISIS schema whether a subfield has a map.
   *
   * @param $field
   *   Field array.
   *
   * @param $subfield
   *   Subfield name.
   *
   * @return
   *   TRUE if subfield has a map, FALSE otherwise.
   */
  public function subfieldHasMap($field, $subfield) {
    if (isset($field['map']['subfields'])) {
      $key = $this->getSubfieldKey($field, $subfield);
      if (isset($field['map']['subfields'][$key])) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Get the key of a subfield entry.
   *
   * @param $field
   *   Field array.
   *
   * @param $subfield
   *   Subfield name.
   *
   * @return
   *   Subfield keys.
   */
  public function getSubfieldKey($field, $subfield) {
    $keys = array_flip($field['subfields']);
    if (isset($keys[$subfield])) {
      return $keys[$subfield];
    }
  }

  /**
   * Remove brackets from strings whithin an array.
   *
   * @param &$values
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
}
