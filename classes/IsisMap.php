<?php

/**
 * Provides mappings and schema functionalities around Cinisis.
 */
class IsisMap extends IsisReader {
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
    return Cinisis::main_field_name($this->format, $key);    
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
      if (isset($field['map']['main'])) {
        // Custom map provided for the main item.
        $dest = $this->mapName($field['map']['main']);
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
   * Guess a method name from a type.
   *
   * @param  $type
   *   Mapping type.
   *
   * @return
   *   Method name.
   */
  static function methodName($type) {
    return 'import'. ucfirst($type);
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
    if (isset($field['map']['main'])) {
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
   *   Subfield key.
   */
  public function getSubfieldKey($field, $subfield) {
    $keys = array_flip($field['subfields']);
    if (isset($keys[$subfield])) {
      return $keys[$subfield];
    }
  }

  /**
   * Get the item key.
   *
   * @param $field
   *   Field array.
   *
   * @param $item
   *   Item name.
   *
   * @return
   *   Item key.
   */
  public function getItemKey($field, $item) {
    if ($item == 'main') {
      return $item;
    }
    else {
      return $this->getSubfieldKey($field, $item);
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
   * Get the array which defines a field.
   *
   * @param $field_key
   *   Field key.
   * 
   * @return
   *   Field array.
   */
  public function getFieldArray($field_key) {
    if (isset($this->format['fields'][$field_key])) {
      return $this->format['fields'][$field_key];
    }

    return NULL;
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
    if (isset($this->format['fields'][$field_key]['subfields'][$subfield_key])) {
      return $this->format['fields'][$field_key]['subfields'][$subfield_key];
    }

    return FALSE;
  }

  /**
   * Get a field name.
   *
   * @param $field_key
   *   Field key.
   *
   * @return
   *   Field name.
   */
  public function getFieldName($field_key) {
    return $this->format['fields'][$field_key]['name'];
  }
}
