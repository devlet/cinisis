<?php

/**
 * Isis entry iterator. Iterates over all entries in
 * the database.
 */
class IsisEntryIterator implements Iterator
{
  private $start;
  private $entry;
  private $entries;

  /**
   * Constructor.
   *
   * @param $class
   *   Instance of IsisConnector or child class.
   *
   * @param $entry
   *   Start entry number to iterate from.
   */ 
  public function __construct($class, $entry = 1) {
    // Read the first value.
    $class->read($entry);

    // Setup.
    $this->class   = $class;
    $this->entry   = $this->start = $entry;
    $this->entries = $class->entries;
  }

  /**
   * Rewind the Iterator to the first element.
   */
  function rewind() {
    $this->entry = $this->start;
  }

  /**
   * Return the key of the current element.
   */
  function key() {
    return $this->entry;
  }

  /**
   * Return the current element.
   */
  function current() {
    return $this->class->result;
  }

  /**
   * Move forward to next element.
   */
  function next() {
    $this->class->read(++$this->entry);
  }

  /**
   * Check if there is a current element after calls to rewind() or next().
   */
  function valid() {
    return $this->entry < $this->entries;
  }  
}
