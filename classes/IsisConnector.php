<?php

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
   */
  public function read($row) {
    // Always store the last result.
    $this->result = $this->isis->db->read($row);

    // Return the result.
    return $this->result;
  }

  /**
   * Get the value of a given field.
   */
  public function getField($field, $row = 0) {
    if (isset($this->result[$field['name']][$row]['field'])) {
      return $this->result[$field['name']][$row]['field'];
    }
  }

  /**
   * Get the value of a given subfield.
   */
  public function getSubfield($field, $subfield, $row = 0) {
    if (isset($this->result[$field['name']][$row]['subfields'][$subfield])) {
      return $this->result[$field['name']][$row]['subfields'][$subfield];
    }
  }

  /**
   * Get the list of subfields from a given field.
   */
  public function getSubfields($field) {
    if (isset($field['subfields'])) {
      return $field['subfields'];
    }
    return array();
  }

  /**
   * Determine which model field an ISIS db field should
   * be mapped to.
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
   * @todo Convert field and subfield names to valid field names.
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
   */
  public function getMapType($field) {
    return isset($field['map']['type']) ? $field['map']['type'] : FALSE;
  }

  /**
   * Check on an ISIS schema whether a field has a map.
   */
  public function fieldHasMap($field) {
    if (isset($field['map']['field'])) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check on an ISIS schema whether a subfield has a map.
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
   */
  public function getSubfieldKey($field, $subfield) {
    $keys = array_flip($field['subfields']);
    if (isset($keys[$subfield])) {
      return $keys[$subfield];
    }
  }
}
