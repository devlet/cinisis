<?php

/**
 * IsisConnector: provides an easy interface to connect an
 * application with Cinisis.
 */
class IsisConnector extends IsisMap {
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
    if (isset($this->result[$field['name']])) {
      return count($this->result[$field['name']]);
    }
    else {
      return 0;
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
  public function getValues($field) {
    if (isset($this->result[$field['name']])) {
      return $this->result[$field['name']];
    }

    return array();
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
  public function getItem($field, $item, $row = 0) {
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
   * @return
   *   Item data.
   */
  public function getItems($field, $item) {
    foreach (new IsisRowIterator($this, $field) as $row) {
      $values[$row] = $this->getItem($field, $item, $row);
    }

    return $values;
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
    $values = array();

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
      if (isset($this->result[$field['name']][$row]))
      {
        $subfields = $this->result[$field['name']][$row];
      }
    }
    else {
      if (isset($this->result[$field['name']][$row]['subfields']))
      {
        $subfields = $this->result[$field['name']][$row]['subfields'];
      }
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
    $values = array();

    foreach (new IsisRowIterator($this, $field) as $row) {
      $values[$row] = $this->getSubfield($field, $subfield, $row);
    }

    return $values;
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
   * Explode brackets for a given item, avoiding null entries.
   *
   * @param $field
   *   Field data.
   *
   * @param $item
   *   Item.
   *
   * @param $row
   *   Row number.
   *
   * @return
   *   Exploded item data.
   */
  public function explodeItem($field, $item, $row) {
    return array_filter($this->explodeValue($this->getItem($field, $item, $row)));
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
   * Check if a field result has an item.
   *
   * @param $field
   *   Field data.
   *
   * @param $item
   *   Item code ('main' for the main item).
   *
   * @param $row
   *   Row number.
   *
   * @return
   *   True if result has the main item, false otherwise.
   */
  public function hasItem($field, $item, $row = 0) {
    if ($item == 'main') {
      $has = $this->hasMainItem($field, $row);
    }
    else {
      $subfield = $this->getSubfieldName($this->getFieldKey($field), $item);
      $has      = $this->hasSubfield($field, $subfield, $row);
    }

    return $has;
  }

  /**
   * Check if a field result has a main item.
   *
   * @param $field
   *   Field data.
   *
   * @param $row
   *   Row number.
   *
   * @return
   *   True if result has the main item, false otherwise.
   */
  public function hasMainItem($field, $row) {
    $value = $this->getMainItem($field, $row);

    if (!empty($value)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Check if a field result and row has a given subfield.
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
   * Return the existing key items for a result.
   *
   * @param $field
   *   Field data.
   *
   * @param $row
   *   Row number.
   *
   * @return
   *   Array with existing item keys
   *
   * @todo
   *   Test.
   */
  public function existingItemKeys($field, $row = 0) {
    $existing = array();

    foreach (new IsisItemIterator($this, $field) as $key => $item) {
      if ($row == $key) {
        $existing[] = $this->getItemKey($field, $item);
      }
    }

    return $existing;
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
    $subfield_key = $this->getSubfieldKey($field, $subfield);

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

      if (array_search($subfield_key, $field['special']) !== FALSE) {
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
