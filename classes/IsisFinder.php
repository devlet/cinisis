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
   *   Field name.
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
    } while (!isset($result[$field]) || count($result[$field]) < 2);

    if (!isset($result[$field]) || count($result[$field]) < 2) {
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
   *   Field name.
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
    } while (!isset($result[$field]));

    if (!isset($result[$field])) {
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
   *   Field name.
   *
   * @param $subfield
   *   Subfield name.
   *
   * @return
   *   Next occurrence.
   *
   * @todo
   *   The subfield might be in any now and not just
   *   in the first one.
   */
  public function nextSubfield($entry = 1, $field, $subfield) {
    $entry--;

    // Query database.
    do {
      $result = $this->read(++$entry);
      if ($entry == $entries) {
        break;
      }
    } while (!isset($result[$field][0][$subfield]));

    if (!isset($result[$field][0][$subfield])) {
      return FALSE;
    }

    return array($entry, $result);
  }
}
