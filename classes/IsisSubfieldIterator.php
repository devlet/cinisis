<?php

/**
 * Isis subfield iterator.
 *
 * @todo
 *   It's not working.
 */
class IsisSubfieldIterator implements Iterator
{
  private $position = 0;
  private $class;
  private $field;
  private $subfields;
  private $keys;

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
    $this->subfields = $class->getSubfields($field);
    $this->keys      = array_values($this->subfields);
    $this->total     = count($this->class->result[$field['name']);
  }

  /**
   * Get the current subfield.
   */
  function subfield() {
    return $this->subfields[$this->key()];
  }

  /**
   * Rewind the Iterator to the first element.
   */
  function rewind() {
    $this->position = 0;
  }

  /**
   * Return the current element.
   */
  function current() {
    $value = $this->class->getSubfield($this->field, $this->subfield());
    return $this->class->explodeValue($value);
  }

  /**
   * Return the key of the current element.
   */
  function key() {
    return $this->keys[$this->position];
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
    return isset($this->keys[$this->position]) && ($this->current() != null);
  }
}
