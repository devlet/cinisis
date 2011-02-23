Cinisis database reader
=======================

Installation
------------

### Getting Cinisis

Cinisis source code can be obtained via git:

    git clone http://git.devlet.com.br/cinisis.git

This documentation covers just installation with Biblio::Isis library
and assumes a Debian like operating system.

### Installing BiblioIsis

The Biblio:Isis can be installed directly from package together with
development files for perl:

    apt-get install libbiblio-isis-perl libperl-dev

### Installing pecl-perl

Then download and build pecl-perl:

    pecl install perl

Due to a bug (see http://pecl.php.net/bugs/bug.php?id=16807), you might
prefer to install it directly from source:

    svn checkout http://svn.php.net/repository/pecl/perl/trunk pecl-perl
    cd pecl-perl
    phpize
    ./configure
    make install

You will still need to enable the extension in your php.ini depending on
how your system is configured.

### Getting spyc

Cinisis config files are written in YAML. You'll need to download Spyc
library from https://code.google.com/p/spyc/ and put the files at
the contrib/ folder.

Configuration
-------------

  - Put your databases into the db folder, one folder per database.
  - Optionally edit config/config.yaml to set the default database.

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
