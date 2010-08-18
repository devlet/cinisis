<?php

/**
 * Methods for auditing an Isis database.
 */
class IsisAudit extends IsisFinder {
  /**
   * Run a standard audit procedure.
   *
   * @todo
   *   Test.
   */
  public function run() {
    foreach ($this->format['fields'] as $field) {
      $field_name = $this->getFieldName($field);
      $repetition = $this->nextRepetition(null, $field_name);

      // Check for repetitions.
      if ($field['repeat'] && !$repetition)
      {
        echo "Field $field_name is configured for repetitions but no repetitions found.\n";
      }
      elseif (!$field['repeat'] && $repetition) {
        echo "Field $field_name is not configured for repetitions but a repetition was found.\n";
      }

      // Check for subfields.
      foreach ($field['subfields'] as $subfield) {
        $subfield_name = $this->getSubfieldName($field, $subfield);
        $next_subfield = $isis->nextSubfield(null, $field_name, $subfield_name);

        if (!$next_subfield) {
          echo "No occurrences found for field $field_name and subfield $subfield_name\n";
        }
      }
    }
  }
}
