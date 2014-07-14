<?php

namespace PhpBinaryReader;

/**
 * @coversDefaultClass \PhpBinaryReader\Endian
 */
class EndianTest extends \PHPUnit_Framework_TestCase
{
    public function testConstants()
    {
        $this->assertEquals(1, Endian::ENDIAN_BIG);
        $this->assertEquals(2, Endian::ENDIAN_LITTLE);
    }

    public function testConvertDoesNothingIfSingleDigit()
    {
        $this->assertEquals(9, Endian::Convert(9));
    }

    public function testConvert()
    {
        $this->assertEquals(128, Endian::convert(2147483648));
    }
}
