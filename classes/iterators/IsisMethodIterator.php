<?php

/**
 * Iterates over all callable methods for database mapping.
 */
class IsisMethodIterator implements Iterator
{
  private $total = 0;
  private $isis;
  private $keys;
  private $position = -1;

  /**
   * Constructor.
   *
   * @param $isis
   *   Instance of IsisConnector or child class.
   *
   * @param $class
   *   Optional class where to look for methods, defaults
   *   to $isis itself.
   */ 
  public function __construct($isis, $class = NULL) {
    // Setup.
    $this->isis  = $isis;
    $this->total = count($isis->fields);
    $this->keys  = array_keys($isis->fields);
    $this->class = ($class == null) ? $isis : $class;

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
    $type = $this->isis->getMapType($this->current());
    return $this->isis->methodName($type);
  }

  /**
   * Return the current element.
   */
  function current() {
    return $this->isis->fields[$this->keys[$this->position]];
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
    return $this->position < $this->total - 1;
  }  
}
