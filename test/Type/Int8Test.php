<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int8
 */
class Int8Test extends AbstractTestCase
{
    /**
     * @var Int8
     */
    public $int8;

    public function setUp()
    {
        $this->int8 = new Int8();
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testUnsignedReaderWithBigEndian($brBig, $brLittle)
    {
        $this->assertEquals(0, $this->int8->read($brBig));
        $this->assertEquals(0, $this->int8->read($brBig));
        $this->assertEquals(0, $this->int8->read($brBig));
        $this->assertEquals(3, $this->int8->read($brBig));
        $this->assertEquals(0, $this->int8->read($brBig));
        $this->assertEquals(2, $this->int8->read($brBig));
        $this->assertEquals(103, $this->int8->read($brBig));
        $this->assertEquals(116, $this->int8->read($brBig));
        $this->assertEquals(101, $this->int8->read($brBig));
        $this->assertEquals(115, $this->int8->read($brBig));
        $this->assertEquals(116, $this->int8->read($brBig));
        $this->assertEquals(33, $this->int8->read($brBig));
        $this->assertEquals(255, $this->int8->read($brBig));
        $this->assertEquals(255, $this->int8->read($brBig));
        $this->assertEquals(255, $this->int8->read($brBig));
        $this->assertEquals(255, $this->int8->read($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testSignedReaderWithBigEndian($brBig, $brLittle)
    {
        $brBig->setPosition(12);
        $this->assertEquals(255, $this->int8->read($brBig));
        $this->assertEquals(-1, $this->int8->readSigned($brBig));
        $this->assertEquals(-1, $this->int8->readSigned($brBig));
        $this->assertEquals(255, $this->int8->read($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testReaderWithLittleEndian($brBig, $brLittle)
    {
        $this->assertEquals(3, $this->int8->read($brLittle));
        $this->assertEquals(0, $this->int8->read($brLittle));
        $this->assertEquals(0, $this->int8->read($brLittle));
        $this->assertEquals(0, $this->int8->read($brLittle));
        $this->assertEquals(2, $this->int8->read($brLittle));
        $this->assertEquals(0, $this->int8->read($brLittle));
        $this->assertEquals(103, $this->int8->read($brLittle));
        $this->assertEquals(116, $this->int8->read($brLittle));
        $this->assertEquals(101, $this->int8->read($brLittle));
        $this->assertEquals(115, $this->int8->read($brLittle));
        $this->assertEquals(116, $this->int8->read($brLittle));
        $this->assertEquals(33, $this->int8->read($brLittle));
        $this->assertEquals(255, $this->int8->read($brLittle));
        $this->assertEquals(255, $this->int8->read($brLittle));
        $this->assertEquals(255, $this->int8->read($brLittle));
        $this->assertEquals(255, $this->int8->read($brLittle));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testSignedReaderWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->setPosition(12);
        $this->assertEquals(255, $this->int8->read($brLittle));
        $this->assertEquals(-1, $this->int8->readSigned($brLittle));
        $this->assertEquals(-1, $this->int8->readSigned($brLittle));
        $this->assertEquals(255, $this->int8->read($brLittle));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBitReaderWithBigEndian($brBig, $brLittle)
    {
        $brBig->setPosition(6);
        $brBig->readBits(4);
        $this->assertEquals(7, $this->int8->read($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBitReaderWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->setPosition(6);
        $brLittle->readBits(4);
        $this->assertEquals(7, $this->int8->read($brLittle));
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian($brBig, $brLittle)
    {
        $brBig->readBits(360);
        $this->int8->read($brBig);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->readBits(360);
        $this->int8->read($brLittle);
    }

    public function testEndian()
    {
        $this->int8->setEndian('X');
        $this->assertEquals('X', $this->int8->getEndian());
    }
}
