<?php

/**
 * Isis normal subfield iterator. Filter out special subfields.
 */
class IsisNormalItemFilterIterator extends FilterIterator {

  /**
   * Filter out special subfields.
   */
  public function accept()
  {
    $field = $this->getInnerIterator()->field;
    $class = $this->getInnerIterator()->class;
    $item  = $this->getInnerIterator()->current();
    return !$class->specialItem($field, $item);
  }
}
