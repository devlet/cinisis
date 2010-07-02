<?php

/**
 * Isis value iterator. Iterates over all values for
 * each result row.
 */
class IsisValueIterator implements Iterator
{
  private $keys;
  private $valueset;
  private $row    = 0;
  private $rows   = 0;
  private $value  = 0;
  private $values = 0;

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
    $this->keys     = array_keys($this->valueset);
    $this->values   = count($this->keys);
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
    return $this->valueset[$this->keys[$this->value]];
  }

  /**
   * Move forward to next element.
   */
  function next() {
    if ($this->value >= $this->values) {
      $this->value = 0;
      ++$this->row;
    }
    else {
      ++$this->value;
    }
  }

  /**
   * Check if there is a current element after calls to rewind() or next().
   */
  function valid() {
    return $this->row <= $this->rows;
  }  
}
