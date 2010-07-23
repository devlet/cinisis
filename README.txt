Cinisis database reader
=======================

Naming conventions
------------------

The following naming conventions are used through Cinisis aiming to help
iterating over all the data from a ISIS database.

  - Database:  an ISIS database.
  - Entry:     a given MFN in the database.
  - Value:     all the data from a given entry in the database.
  - Field:     a numbered set of values from a given entry.
  - Row:       a single value from a given field.
  - Main item: the data in a row without a qualifier.
  - Subfield:  every data in a row within a qualifier.
  - Item:      either a main item or subfield withing a row.

Example:

  MFN 1 with entry
  10: First  row of field 10^aWith a subfield^bAnd another one
  10: Second row of field 10^bJust with the second subfield
  20: This is the main item^yAnd this is another item

For that entry we have fields 10 and 20, where field 10 has two rows (i.e, two
repetitions). The main field is the data wich is has no qualifier (^) and a
subfield is the data with qualifiers (like subfields a and b from above).
