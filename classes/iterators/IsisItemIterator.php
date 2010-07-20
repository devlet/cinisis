<?php

/**
 * Isis field iterator. Iterates over a field for each result row.
 */
class IsisItemIterator implements Iterator
{
  private $keys;
  private $fieldset;
  private $row   = 0;
  private $rows  = 0;
  private $item  = 0;
  private $items = 0;

  /**
   * Constructor.
   *
   * @param $class
   *   Instance of IsisConnector or child class.
   *
   * @param $field
   *   Field to iterate over.
   *
   * @param $main
   *   Control to which item the main field should be mapped to.
   *   By default no mapping is made.
   */ 
  public function __construct($class, $field, $main = false) {
    // Setup.
    $this->class    = $class;
    $this->field    = $field;
    $this->rows     = $class->getRows($field);

    // Handle subfields.
    $this->fieldset = $class->getSubfieldList($field);
    $this->keys     = array_keys($this->fieldset);
    $this->items    = count($this->keys);

    // Sum up main item.
    $this->fieldset['main'] = $class->getMainItemName($field);
    $this->keys[]           = 'main';
    $this->items++;
  }

  /**
   * Rewind the Iterator to the first element.
   */
  function rewind() {
    $this->row  = 0;
    $this->item = 0;
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
    return $this->fieldset[$this->keys[$this->item]];
  }

  /**
   * Move forward to next element.
   */
  function next() {
    if ($this->item >= $this->items) {
      $this->item = 0;
      ++$this->row;
    }
    else {
      ++$this->item;
    }
  }

  /**
   * Check if there is a current element after calls to rewind() or next().
   */
  function valid() {
    return $this->row <= $this->rows;
  }  
}
