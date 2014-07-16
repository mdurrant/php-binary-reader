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
        $this->assertEquals(1, Endian::BIG);
        $this->assertEquals(2, Endian::LITTLE);
    }

    public function testConvertDoesNothingIfSingleDigit()
    {
        $endian = new Endian();
        $this->assertEquals(9, $endian->Convert(9));
    }

    public function testConvert()
    {
        $endian = new Endian();
        $this->assertEquals(128, $endian->convert(2147483648));
    }
}
