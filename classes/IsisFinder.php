<?php

/**
 * Provides Isis Database search methods.
 */
class IsisFinder extends IsisConnector {
  /**
   * Find the next repetition of a field.
   *
   * @param $entry
   *   Start entry number to begin the search.
   *
   * @param $field
   *   Field data.
   *
   * @return
   *   Next repetition entry and result.
   */
  public function nextRepetition($entry = 1, $field) {
    $entry--;

    // Query database.
    do {
      $result = $this->read(++$entry);
      if ($entry == $entries) {
        break;
      }
    } while (!isset($result[$field['name']]) || count($result[$field['name']]) < 2);

    if (!isset($result[$field['name']]) || count($result[$field['name']]) < 2) {
      return FALSE;
    }

    return array($entry, $result);
  }

  /**
   * Find the next occurrence of a field.
   *
   * @param $entry
   *   Start entry number to begin the search.
   *
   * @param $field
   *   Field data.
   *
   * @return
   *   Next occurrence.
   */
  public function nextField($entry = 1, $field) {
    $entry--;

    // Query database.
    do {
      $result = $this->read(++$entry);
      if ($entry == $entries) {
        break;
      }
    } while (!isset($result[$field['name']]));

    if (!isset($result[$field['name']])) {
      return FALSE;
    }

    return array($entry, $result);
  }

  /**
   * Find the next occurrence of a subfield.
   *
   * @param $entry
   *   Start entry number to begin the search.
   *
   * @param $field
   *   Field data.
   *
   * @param $subfield
   *   Subfield name.
   *
   * @return
   *   Next occurrence.
   */
  public function nextSubfield($entry = 1, $field, $subfield) {
    $entry--;

    // Query database.
    do {
      $result = $this->read(++$entry);
      if ($entry == $entries) {
        break;
      }
      $has = $this->hasSubfieldInRows($field, $subfield);
    } while ($has === FALSE);

    if (!$this->hasSubfield($field, $subfield, $has)) {
      return FALSE;
    }

    return array($entry, $result);
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
   * @return
   *   True if result has the subfield, false otherwise.
   */  
  public function hasSubfieldInRows($field, $subfield) {
    foreach (new IsisRowIterator($this, $field) as $row) {
      if ($this->hasSubfield($field, $subfield, $row)) {
        return $row;
      }
    }

    return FALSE;
  }
}