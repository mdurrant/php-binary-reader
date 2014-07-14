<?php

namespace PhpBinaryReader;

/**
 * @coversDefaultClass \PhpBinaryReader\BitMask
 */
class BitMaskTest extends \PHPUnit_Framework_TestCase
{
    public function testBitMaskArray()
    {
        $expected = [
            [0x00, 0xFF],
            [0x01, 0x7F],
            [0x03, 0x3F],
            [0x07, 0x1F],
            [0x0F, 0x0F],
            [0x1F, 0x07],
            [0x3F, 0x03],
            [0x7F, 0x01],
            [0xFF, 0x00]
        ];

        $this->assertEquals($expected, BitMask::getBitMasks());
    }

    public function testLoMaskIsReturnedByBit()
    {
        $this->assertEquals(0x03, BitMask::getMask(2, BitMask::MASK_LO));
        $this->assertEquals(0xFF, BitMask::getMask(8, BitMask::MASK_LO));
    }

    public function testHiMaskIsReturnedByBit()
    {
        $this->assertEquals(0x03, BitMask::getMask(6, BitMask::MASK_HI));
        $this->assertEquals(0xFF, BitMask::getMask(0, BitMask::MASK_HI));
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionIsThrownIfMaskTypeIsUnsupported()
    {
        BitMask::getMask(5, 5);
    }
}
