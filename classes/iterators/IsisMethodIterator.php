<?php

/**
 * Iterates over all callable methods for database mapping.
 */
class IsisMethodIterator implements Iterator
{
  private $total = 0;
  private $class;
  private $keys;
  private $position = -1;

  /**
   * Constructor.
   *
   * @param $class
   *   Instance of IsisConnector or child class.
   */ 
  public function __construct($class) {
    // Setup.
    $this->class = $class;
    $this->total = count($class->fields);
    $this->keys  = array_keys($class->fields);

    // Find the first valid occurrence.
    $this->next();
  }

  /**
   * Rewind the Iterator to the first valid element.
   */
  function rewind() {
    $this->position = -1;
    $this->next();
  }

  /**
   * Return the key of the current element.
   */
  function key() {
    $type = $this->class->getMapType($this->current());
    return $this->class->methodName($type);
  }

  /**
   * Return the current element.
   */
  function current() {
    return $this->class->fields[$this->keys[$this->position]];
  }

  /**
   * Move forward to next element. The method should be callable, otherwise
   * we move to the next position.
   */
  function next() {
    do {
      ++$this->position;
    }
    while (!is_callable(array($this->class, $this->key())) && $this->valid());
  }

  /**
   * Check if there is a current element after calls to rewind() or next().
   */
  function valid() {
    return $this->position <= $this->total;
  }  
}
