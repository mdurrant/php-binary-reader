<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Bit
 */
class BitTest extends AbstractTestCase
{
    /**
     * @dataProvider binaryReaders
     */
    public function testUnsignedBitReader($brBig, $brLittle)
    {
        $this->assertEquals(3, $brBig->readUBits(32));
        $this->assertEquals(3, $brLittle->readUBits(32));
        $this->assertEquals(2, $brBig->readUBits(16));
        $this->assertEquals(2, $brLittle->readUBits(16));
        $this->assertEquals(103, $brBig->readUBits(8));
        $this->assertEquals(103, $brLittle->readUBits(8));

        $brBig->setPosition(0);
        $brLittle->setPosition(0);

        $brBig->readUBits(28);
        $brLittle->readUBits(28);

        $this->assertEquals(0, $brBig->readUBits(6));
        $this->assertEquals(2, $brLittle->readUBits(6));
        $this->assertEquals(0, $brBig->readUBits(4));
        $this->assertEquals(0, $brLittle->readUBits(4));
        $this->assertEquals(0, $brBig->readUBits(2));
        $this->assertEquals(0, $brLittle->readUBits(2));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testSignedBitReader($brBig, $brLittle)
    {
        $this->assertEquals(50331648, $brBig->readBits(32));
        $this->assertEquals(3, $brLittle->readBits(32));
        $this->assertEquals(512, $brBig->readBits(16));
        $this->assertEquals(2, $brLittle->readBits(16));
        $this->assertEquals(103, $brBig->readBits(8));
        $this->assertEquals(103, $brLittle->readBits(8));

        $brBig->setPosition(0);
        $brLittle->setPosition(0);

        $brBig->readBits(28);
        $brLittle->readBits(28);

        $this->assertEquals(0, $brBig->readBits(6));
        $this->assertEquals(2, $brLittle->readBits(6));
        $this->assertEquals(0, $brBig->readBits(4));
        $this->assertEquals(0, $brLittle->readBits(4));
        $this->assertEquals(0, $brBig->readBits(2));
        $this->assertEquals(0, $brLittle->readBits(2));
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testExceptionBitsBigEndian($brBig, $brLittle)
    {
        $brBig->setPosition(16);
        $brBig->readBits(16);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testExceptionBitsLittleEndian($brBig, $brLittle)
    {
        $brLittle->setPosition(16);
        $brLittle->readBits(16);
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     * @dataProvider binaryReaders
     */
    public function testExceptionInvalidBitCountBigEndian($brBig, $brLittle)
    {
        $brBig->readBits('foo');
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     * @dataProvider binaryReaders
     */
    public function testExceptionInvalidBitCountLittleEndian($brBig, $brLittle)
    {
        $brLittle->readBits('foo');
    }
}
