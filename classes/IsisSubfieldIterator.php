<?php

// TODO: not working
class IsisSubfieldIterator implements Iterator
{
    private $position = 0;
    private $class;
    private $field;
    private $subfields;
    private $keys;

    public function __construct($class, $field)
    {
      $this->class     = $class;
      $this->field     = $field;
      $this->subfields = $class->getSubfields($field);
      $this->keys      = array_values($this->subfields);
      print_r($field);
    }

    function subfield()
    {
      return $this->subfields[$this->key()];
    }

    function rewind()
    {
      $this->position = 0;
    }

    function current()
    {
      $value = $this->class->getSubfield($this->field, $this->subfield());
      return $this->class->explodeValue($value);
    }

    function key()
    {
      return $this->keys[$this->position];
    }

    function next()
    {
      ++$this->position;
    }

    function valid()
    {
      return isset($this->keys[$this->position]) && ($this->current() != null);
    }
}
