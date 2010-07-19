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
   * Get the main field name.
   *
   * @param $field
   *   Field data from ISIS database schema.
   *
   * @return
   *   Main field name.
   */
  public function getMainItemName($field) {
    $key = $this->getFieldKey($field);
    return $this->isis->db->main_field_name($key);    
  }

  /**
   * Whether to join field and subfields in a single array.
   *
   * @return
   *   Boolean.
   */
  public function joinSubfields() {
    if ($this->isis->db->join_subfields()) {
      return TRUE;
    }

    return FALSE;
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
  public function getValues($field) {
    if (isset($this->result[$field['name']])) {
      return $this->result[$field['name']];
    }

    return array();
  }

  /**
   * Get the number of resulting rows for a given field.
   *
   * @param $field
   *   Field array.
   *
   * @return
   *   Number of rows.
   */
  public function getRows($field) {
    return count($this->result[$field['name']]);
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
  public function getMainItem($field, $row = 0) {
    $name = $this->getMainItemName($field);

    if (isset($this->result[$field['name']][$row][$name])) {
      return $this->result[$field['name']][$row][$name];
    }
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
  public function getMainItems($field) {
    foreach (new IsisRowIterator($this, $field) as $row) {
      $values[$row] = $this->getMainItem($field, $row);
    }

    return $values;
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
    if ($this->joinSubfields()) {
      $subfields = $this->result[$field['name']][$row];
    }
    else {
      $subfields = $this->result[$field['name']][$row]['subfields'];
    }

    if (isset($subfields[$subfield])) {
      return $subfields[$subfield];
    }
  }

  /**
   * Get all values of a given subfield.
   *
   * @param $field
   *   Field array.
   *
   * @param $subfield
   *   Subfield name.
   *
   * @return
   *   Subfield data.
   */
  public function getSubfields($field, $subfield) {
    foreach (new IsisRowIterator($this, $field) as $row) {
      $values[$row] = $this->getSubfield($field, $subfield, $row);
    }

    return $values;
  }

  /**
   * Get both main field or subfields from a given field and row.
   *
   * @param $field
   *   field array.
   *
   * @param $item
   *   item name (field or subfield).
   *
   * @param $row
   *   row number.
   *
   * @return
   *   Item data.
   */
  public function getItem($field, $item, $row) {
    $main_field = $this->getMainItemName($field);

    if ($field == $main_field) {
      return $this->getMainItem($field, $row);
    }
    else {
      return $this->getSubfield($field, $item, $row);
    }
  }

  /**
   * Get all rows both main field or subfields from a given field.
   *
   * @param $field
   *   field array.
   *
   * @param $item
   *   item name (field or subfield).
   *
   * @param $row
   *   row number.
   *
   * @return
   *   Item data.
   *
   * @todo
   *   Rename to getItem?
   */
  public function getItems($field, $item) {
    foreach (new IsisRowIterator($this, $field) as $row) {
      $values[$row] = $this->getItem($field, $item, $row);
    }

    return $values;
  }

  /**
   * Get the list of subfields from a given field.
   *
   * @param $field
   *   Field array.
   */
  public function getSubfieldList($field) {
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
   */
  public function getMap($field, $subfield = NULL) {
    if ($subfield == NULL) {
      if (isset($field['map']['field'])) {
        // Custom map provided.
        $dest = $this->mapName($field['map']['field']);
      }
      else {
        // Default map.
        $dest = $this->mapName($field['name']);
      }
    }
    else {
      $key = $this->getSubfieldKey($field, $subfield);

      if (isset($field['map']['subfields'][$key])) {
        // Custom map provided.
        $dest = $this->mapName($field['map']['subfields'][$key]);
      }
      else {
        // Default map.
        $dest = $this->mapName($subfield);
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
   * Get the key of a field entry.
   *
   * @param $field
   *   Field array.
   *
   * @return
   *   Field key.
   */
  public function getFieldKey($field) {
    return array_search($field, $this->format['fields']);
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
   * Explode brackets for a given subfield, avoiding null entries.
   *
   * @param $field
   *   Field data.
   *
   * @param $subfield
   *   Subfield.
   *
   * @param $row
   *   Row number.
   *
   * @return
   *   Exploded subfield data.
   */
  public function explodeSubfield($field, $subfield, $row) {
    return array_filter($this->explodeValue($this->getSubfield($field, $subfield, $row)));
  }

  /**
   * Filter brackets for a given subfield.
   *
   * @param $field
   *   Field data.
   *
   * @param $subfield
   *   Subfield.
   *
   * @param $row
   *   Row number.
   *
   * @return
   *   Filterd subfield data.
   */
  public function filterSubfield($field, $subfield, $row) {
    return $this->filterBrackets($this->getSubfield($field, $subfield, $row));
  }

  /**
   * Normalize field names.
   *
   * @param  $name
   *   Field name
   *
   * @return
   *   Normalized field name
   */
  static function normalizeFieldName($name) {
    return ucfirst(preg_replace('/[^a-z0-9_]/', '', strtolower($name)));
  }

  /**
   * Build a map name.
   *
   * @param $name
   *   Field name
   *
   * @return
   *   Map name
   */
  static function mapName($name) {
    return 'set'. self::normalizeFieldName($name);
  }

  /**
   * Check if a field result has a given subfield.
   *
   * @param $field
   *   Field data.
   *
   * @param $subfield
   *   Subfield.
   *
   * @param $row
   *   Row number.
   *
   * @return
   *   True if result has the subfield, false otherwise.
   */
  public function hasSubfield($field, $subfield, $row) {
    $value = $this->getSubfield($field, $subfield, $row);

    if (!empty($value)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Get a subfield name.
   *
   * @param $field_key
   *   Field key.
   *
   * @param $subfield_key
   *   Subfield key. 
   *
   * @return
   *   Subfield name.
   */
  public function getSubfieldName($field_key, $subfield_key) {
    return $this->format['fields'][$field_key]['subfields'][$subfield_key];
  }

  /**
   * Check if a field and subfield match a given condition.
   *
   * @param $field
   *   Field data.
   *
   * @param $subfield
   *   Subfield.
   *
   * @param $key
   *   Field key.
   *
   * @param $subkey
   *   Subfield key. 
   *
   * @return
   *   True if condition match, false otherwise.
   */
  public function hasFieldSubfieldCondition($field, $subfield, $key, $subkey) {
    $field_key    = $this->getFieldKey($field);
    $subdield_key = $this->getSubfieldKey($field, $subfield);

    if ($field_key == $key && $subfield_key == $subkey) {
      return true;
    }

    return false;
  }

  /**
   * Deal with special items.
   *
   * @param $field
   *   Field data from ISIS database schema.
   *
   * @param $subfield
   *   Subfield name.
   *
   * @param $return
   *   Specify return type.
   *
   * @return
   *   True if special subfield, false otherwise of special return type
   */
  public function specialItem($field, $subfield, $return = 'boolean') {
    if (isset($field['special'])) {
      $field_key    = $this->getFieldKey($field);
      $subfield_key = $this->getSubfieldKey($field, $subfield);
      $name         = $field['name'] .':'. $subfield;
      $code         = $field_key .':'. $subfield_key;

      if (array_search($subfield_key, $field['special'])) {
        if ($return == 'boolean') {
          return true;
        }
        elseif ($return == 'code') {
          return $code;
        }

        return $name;
      }
    }

    return false;
  }
}
