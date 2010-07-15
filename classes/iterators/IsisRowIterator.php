<?php

/**
 * Iterates over all rows from a field result.
 */
class IsisRowIterator implements Iterator
{
  private $row  = 0;
  private $rows = 0;

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
    $this->rows = $class->getRows($field);
  }

  /**
   * Rewind the Iterator to the first element.
   */
  function rewind() {
    $this->row = 0;
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
    return $this->row;
  }

  /**
   * Move forward to next element.
   */
  function next() {
    ++$this->row;
  }

  /**
   * Check if there is a current element after calls to rewind() or next().
   */
  function valid() {
    return $this->row <= $this->rows;
  }  
}  
