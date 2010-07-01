<?php

/**
 * Iterates over all rows from a field result.
 */
class IsisRowIterator implements Iterator
{
  private $position = 0;
  private $total    = 0;

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
    $this->total = $class->getRows($field);
  }

  /**
   * Rewind the Iterator to the first element.
   */
  function rewind() {
    $this->position = 0;
  }

  /**
   * Return the key of the current element.
   */
  function key() {
    return $this->position;
  }

  /**
   * Return the current element.
   */
  function current() {
    return $this->position;
  }

  /**
   * Move forward to next element.
   */
  function next() {
    ++$this->position;
  }

  /**
   * Check if there is a current element after calls to rewind() or next().
   */
  function valid() {
    return $this->position <= $this->total;
  }  
}  
