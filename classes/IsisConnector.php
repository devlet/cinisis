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
   * Get the value of a given field.
   *
   * @param $field
   *   Field number.
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
   *   Field name.
   *
   * @param $subfield
   *   Subfield name.
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
   *   Subfield name.
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
   *   Field number.
   *
   * @param $subfield
   *   Subfield name. 
   *
   * @retrn
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
   *   Field number.
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
   *   Field number.
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
   *   Field number.
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
   *   Field number.
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
}
