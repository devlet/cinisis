<?php

/**
 * Isis normal subfield iterator. Filter out special subfields.
 */
class IsisNormalSubfieldFilterIterator extends FilterIterator {
  public function accept()
  {
    $field    = $this->getInnerIterator()->field;
    $class    = $this->getInnerIterator()->class;
    $subfield = $this->getInnerIterator()->current();
    return !$class->specialSubfield($field, $subfield);
  }
}
