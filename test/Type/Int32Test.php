<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int32
 */
class Int32Test extends AbstractTestCase
{
    /**
     * @var Int32
     */
    public $int32;

    public function setUp()
    {
        $this->int32 = new Int32();
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testUnsignedReaderWithBigEndian($brBig, $brLittle)
    {
        $this->assertEquals(3, $this->int32->read($brBig));
        $this->assertEquals(157556, $this->int32->read($brBig));
        $this->assertEquals(1702065185, $this->int32->read($brBig));
        $this->assertEquals(4294967295, $this->int32->read($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testSignedReaderWithBigEndian($brBig, $brLittle)
    {
        $brBig->setPosition(12);
        $this->assertEquals(-1, $this->int32->readSigned($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testReaderWithLittleEndian($brBig, $brLittle)
    {
        $this->assertEquals(3, $this->int32->read($brLittle));
        $this->assertEquals(1952907266, $this->int32->read($brLittle));
        $this->assertEquals(561279845, $this->int32->read($brLittle));
        $this->assertEquals(4294967295, $this->int32->read($brLittle));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testSignedReaderWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->setPosition(12);
        $this->assertEquals(-1, $this->int32->readSigned($brLittle));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBitReaderWithBigEndian($brBig, $brLittle)
    {
        $brBig->setPosition(6);
        $brBig->readBits(4);
        $this->assertEquals(122050356, $this->int32->read($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBitReaderWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->setPosition(6);
        $brLittle->readBits(4);
        $this->assertEquals(122107476, $this->int32->read($brLittle));
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian($brBig, $brLittle)
    {
        $brBig->readBits(360);
        $this->int32->read($brBig);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->readBits(360);
        $this->int32->read($brLittle);
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testAlternateMachineByteOrderSigned($brBig, $brLittle)
    {
        $brLittle->setMachineByteOrder(Endian::ENDIAN_BIG);
        $brLittle->setEndian(Endian::ENDIAN_LITTLE);
        $this->assertEquals(3, $this->int32->readSigned($brLittle));
    }

    public function testEndian()
    {
        $this->int32->setEndianBig('X');
        $this->assertEquals('X', $this->int32->getEndianBig());

        $this->int32->setEndianLittle('Y');
        $this->assertEquals('Y', $this->int32->getEndianLittle());
    }

}
