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
  public function nextRepetition($field, $entry = 1) {
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
  public function nextField($field, $entry = 1) {
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
  public function nextSubfield($field, $subfield, $entry = 1) {
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

  /**
   * Search the next match inside a result.
   *
   * @param $field
   *   Field data.
   *
   * @param $item
   *   Item name (main field or subfield).
   *
   * @param $search
   *   Search token.
   *
   * @param $entry
   *   Start entry number to begin the search.
   *
   * @param $match
   *   Set to false do find the next result where the
   *   item has not the token specified in $search.
   */
  public function nextResult($field, $item, $search, $entry = 1, $match = TRUE) {
    do {
      // Get the next entry that has the field/subfield we'll look at.
      if ($item = 'main') {
        $next = $this->nextField($field, $entry);
      }
      else {
        $next = $this->nextSubfield($field, $item, $entry);
      }

      // Check if there's a next matching field/subfield.
      if ($next === FALSE) {
        break;
      }
      else {
        list($entry, $result) = $next;
      }

      // Search for any occurrence.
      foreach (new IsisRowIterator($this, $field) as $row) {
        if ($item = 'main') {
          $verify = $this->matchMainItem($field, $row, $search, $match);
        }
        else {
          $verify = $this->matchSubfield($field, $row, $search, $match);
        }

        if ($verify !== FALSE)
        {
          return array($entry, $result);
        }
      }
    }
    while ($entry++);
  }

  /**
   * Check if a main item match a given value.
   *
   * @param $field
   *   Field data.
   *
   * @param $row
   *   Row number.
   *
   * @param $search
   *   Search token.
   *
   * @param $match
   *   Set to false do find the next result where the
   *   item has not the token specified in $search.
   *
   * @return
   *   True if match, false otherwise.
   */
  public function matchMainItem($field, $row, $search, $match) {
    if ($this->getMainItem($field, $row) == $search) {
      if ($match) {
        return TRUE;
      }
    }
    elseif (!$match) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Check if a subfield match a given value.
   *
   * @param $field
   *   Field data.
   *
   * @param $subfield
   *   Subfield name.
   *
   * @param $row
   *   Row number.
   *
   * @param $search
   *   Search token.
   *
   * @param $match
   *   Set to false do find the next result where the
   *   item has not the token specified in $search.
   *
   * @return
   *   True if match, false otherwise.
   */
  public function matchSubfield($field, $subfield, $row, $search, $match) {
    if ($this->getSubfield($field, $subfield, $row) == $search) {
      if ($match) {
        return TRUE;
      }
      elseif (!$match) {
        return TRUE;
      }
    }

    return FALSE;
  }
}
