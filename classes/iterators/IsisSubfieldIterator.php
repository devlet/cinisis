<?php

/**
 * Isis subfield iterator. Iterates over all subfields for
 * each result row.
 */
class IsisSubfieldIterator implements Iterator
{
  private $keys;
  private $fieldset;
  private $row       = 0;
  private $rows      = 0;
  private $subfield  = 0;
  private $subfields = 0;

  /**
   * Constructor.
   *
   * @param $class
   *   Instance of IsisConnector or child class.
   *
   * @param $field
   *   Field to iterate over.
   */ 
  public function __construct($class, $field) {
    $this->class     = $class;
    $this->field     = $field;
    $this->rows      = $class->getRows($field);
    $this->fieldset  = $class->getSubfieldList($field);
    $this->keys      = array_keys($this->fieldset);
    $this->subfields = count($this->keys);
  }

  /**
   * Rewind the Iterator to the first element.
   */
  function rewind() {
    $this->row      = 0;
    $this->subfield = 0;
  }

  /**
   * Return the key of the current element.
   */
  function key() {
    return $this->row;
  }

  /**
   * Return the current element.
   */
  function current() {
    return $this->fieldset[$this->keys[$this->subfield]];
  }

  /**
   * Move forward to next element.
   */
  function next() {
    if ($this->subfield >= $this->subfields) {
      $this->subfield = 0;
      ++$this->row;
    }
    else {
      ++$this->subfield;
    }
  }

  /**
   * Check if there is a current element after calls to rewind() or next().
   */
  function valid() {
    return $this->row <= $this->rows;
  }  
}
