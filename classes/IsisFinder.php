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
    foreach(new IsisEntryIterator($this, $entry) as $entry => $result) {
      if (count($this->getValues($field)) >= 2) {
        return array($entry, $result);
      }
    }

    return FALSE;
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
    foreach(new IsisEntryIterator($this, $entry) as $entry => $result) {
      if (count($this->getValues($field)) > 0) {
        return array($entry, $result);
      }
    }

    return FALSE;
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
    foreach(new IsisEntryIterator($this, $entry) as $entry => $result) {
      if ($this->hasSubfieldInRows($field, $subfield) !== FALSE) {
        return array($entry, $result);
      }
    }

    return FALSE;
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
