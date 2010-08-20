<?php

/**
 * Methods for auditing an Isis database.
 */
class IsisAudit extends IsisFinder {
  /**
   * @var $log
   *   Log messages. 
   */
  var $log;

  /**
   * Class logger.
   *
   * @param $message
   *   Log message.
   */
  function logger($message) {
    $this->log[] = $message;
  }

  /**
   * Run a standard audit procedure.
   */
  public function run() {
    foreach ($this->format['fields'] as $field) {
      $repetition = $this->nextRepetition($field);

      // Check for repetitions.
      if ($field['repeat'] && !$repetition) {
        $this->logger("Field ". $field['name'] ." is configured for repetitions but no repetitions found.");
      }
      elseif (!$field['repeat'] && $repetition) {
        $this->logger("Field ". $field['name'] ." is not configured for repetitions but a repetition was found for entry ". $repetition[0] .".");
      }

      // Check for subfields.
      if (isset($field['subfields'])) {
        foreach ($field['subfields'] as $subfield) {
          $next_subfield = $this->nextSubfield($field, $subfield);

          if (!$next_subfield) {
            $this->logger("No occurrences found for field ". $field['name'] ." and subfield $subfield.");
          }
        }
      }
    }
  }
}
