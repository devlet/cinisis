<?php

/**
 * Isis field iterator. Iterates over all field main values for
 * each result row.
 *
 * @todo
 *   Support for 'join_subfields'
 */
class IsisMainFieldIterator implements Iterator
{
  private $valueset;
  private $row    = 0;
  private $rows   = 0;

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
    $this->rows     = $class->getRows($field);
    $this->valueset = $class->getValues($field);
  }

  /**
   * Rewind the Iterator to the first element.
   */
  function rewind() {
    $this->row   = 0;
    $this->value = 0;
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
    return $this->valueset[$this->row]['field'];
  }

  /**
   * Move forward to next element.
   */
  function next() {
    do {
      ++$this->row;
    }
    while ($this->current_null() && $this->has_more_rows());
  }

  /**
   * Check if there are more rows.
   */
  function has_more_rows() {
    return $this->row <= $this->rows;
  }

  /**
   * Check if the current value is null.
   */
  function current_null() {
    return $this->current() == NULL;
  }

  /**
   * Check if there is a current element after calls to rewind() or next().
   */
  function valid() {
    return $this->has_more_rows() && !$this->current_null();
  }  
}
