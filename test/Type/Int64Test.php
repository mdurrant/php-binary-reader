<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int64
 */
class Int64Test extends AbstractTestCase
{
    /**
     * @var Int64
     */
    public $int64;

    public function setUp()
    {
        $this->int64 = new Int64();
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testUnsignedReaderWithBigEndian($brBig, $brLittle)
    {
        $this->assertEquals(12885059444, $this->int64->read($brBig));
        $this->assertEquals(7310314309530157055, $this->int64->read($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testSignedReaderWithBigEndian($brBig, $brLittle)
    {
        $brBig->setPosition(12);
        $this->assertEquals(-3229614080, $this->int64->readSigned($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testReaderWithLittleEndian($brBig, $brLittle)
    {
        $this->assertEquals(8387672839590772739, $this->int64->read($brLittle));
        $this->assertEquals(18446744069975864165, $this->int64->read($brLittle));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testSignedReaderWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->setPosition(12);
        $this->assertEquals(4575657225703391231, $this->int64->readSigned($brLittle));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBitReaderWithBigEndian($brBig, $brLittle)
    {
        $brBig->setPosition(6);
        $brBig->readBits(4);
        $this->assertEquals(504403158265495567, $this->int64->read($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBitReaderWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->setPosition(6);
        $brLittle->readBits(4);
        $this->assertEquals(504403158265495567, $this->int64->read($brLittle));
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian($brBig, $brLittle)
    {
        $brBig->readBits(360);
        $this->int64->read($brBig);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->readBits(360);
        $this->int64->read($brLittle);
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testAlternateMachineByteOrderSigned($brBig, $brLittle)
    {
        $brLittle->setMachineByteOrder(Endian::ENDIAN_BIG);
        $brLittle->setEndian(Endian::ENDIAN_LITTLE);
        $this->assertEquals(8387672839590772739, $this->int64->readSigned($brLittle));
    }

    public function testEndian()
    {
        $this->int64->setEndianBig('X');
        $this->assertEquals('X', $this->int64->getEndianBig());

        $this->int64->setEndianLittle('Y');
        $this->assertEquals('Y', $this->int64->getEndianLittle());
    }

}
