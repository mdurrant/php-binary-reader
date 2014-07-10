PhpBinaryReader
===
[![Build Status](https://travis-ci.org/mdurrant/php-binary-reader.svg)](https://travis-ci.org/mdurrant/php-binary-reader)
[![Code Coverage](https://scrutinizer-ci.com/g/mdurrant/php-binary-reader/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mdurrant/php-binary-reader/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mdurrant/php-binary-reader/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mdurrant/php-binary-reader/?branch=master)

The primary purpose of this binary reader is to accept a string of file contents and provide a familiar set of methods
to read various data types. This is class is rough, early in development and no warranties are provided. Please see the 
license file for usage guidelines.

Contributing
---
Contributions must follow the PSR2 coding standards.

Example Usage
---
```
$fileData = file_get_contents('somefile.bin');
$br = new BinaryReader($fileData, 'little');
$magic = $br->readUInt32();
$offset = $br->readUInt16();
$length = $br->readUInt16();
```

Methods
---
**__construct($str, $endian)** a string must be provided to use this class, an endian is optional (big|little), it will default to big if not provided

**readUInt8()** will return a single 8 bit byte as an unsigned integer

**readUInt16()** will return a 16-bit short as an unsigned integer

**readUInt32()** will return a 32-bit unsigned integer

**readBytes($count)** will return up to 4 bytes (readBits constraint), this will be refactored later to support more

**readBits($count)** will return up to 32 bits, allows reading data at the bit level

**align($move)** will align the pointer back to 0 bits, it will move it to the next byte if $move = true

**readString($len)** expects a length parameter, it will read the number of characters and return a string

**isEof()** will return true if the pointer is on the last byte of the file

Acknowledgements
---
Significant portions of the work is based on Graylin Kim's Python bit/byte reader in _sc2reader: https://github.com/GraylinKim/sc2reader