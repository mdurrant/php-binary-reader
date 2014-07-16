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

        $bitmask = new Bitmask();
        $this->assertEquals($expected, $bitmask->getBitMasks());
    }

    public function testLoMaskIsReturnedByBit()
    {
        $bitmask = new Bitmask();
        $this->assertEquals(0x03, $bitmask->getMask(2, BitMask::MASK_LO));
        $this->assertEquals(0xFF, $bitmask->getMask(8, BitMask::MASK_LO));
    }

    public function testHiMaskIsReturnedByBit()
    {
        $bitmask = new Bitmask();
        $this->assertEquals(0x03, $bitmask->getMask(6, BitMask::MASK_HI));
        $this->assertEquals(0xFF, $bitmask->getMask(0, BitMask::MASK_HI));
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionIsThrownIfMaskTypeIsUnsupported()
    {
        $bitmask = new Bitmask();
        $bitmask->getMask(5, 5);
    }
}
