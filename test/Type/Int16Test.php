<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int16
 */
class Int16Test extends AbstractTestCase
{
    /**
     * @var Int16
     */
    public $int16;

    public function setUp()
    {
        $dataBig = file_get_contents(__DIR__ . '/../asset/testfile-big.bin');
        $dataLittle = file_get_contents(__DIR__ . '/../asset/testfile-little.bin');

        $this->int16 = new Int16();
        $brBig = new BinaryReader($dataBig, Endian::ENDIAN_BIG);
        $brLittle = new BinaryReader($dataLittle, Endian::ENDIAN_LITTLE);
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testUnsignedReaderWithBigEndian($brBig, $brLittle)
    {
        $this->assertEquals(0, $this->int16->read($brBig));
        $this->assertEquals(3, $this->int16->read($brBig));
        $this->assertEquals(2, $this->int16->read($brBig));
        $this->assertEquals(26484, $this->int16->read($brBig));
        $this->assertEquals(25971, $this->int16->read($brBig));
        $this->assertEquals(29729, $this->int16->read($brBig));
        $this->assertEquals(65535, $this->int16->read($brBig));
        $this->assertEquals(65535, $this->int16->read($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testSignedReaderWithBigEndian($brBig, $brLittle)
    {
        $brBig->setPosition(12);
        $this->assertEquals(-1, $this->int16->readSigned($brBig));
        $this->assertEquals(65535, $this->int16->read($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testReaderWithLittleEndian($brBig, $brLittle)
    {
        $this->assertEquals(3, $this->int16->read($brLittle));
        $this->assertEquals(0, $this->int16->read($brLittle));
        $this->assertEquals(2, $this->int16->read($brLittle));
        $this->assertEquals(29799, $this->int16->read($brLittle));
        $this->assertEquals(29541, $this->int16->read($brLittle));
        $this->assertEquals(8564, $this->int16->read($brLittle));
        $this->assertEquals(65535, $this->int16->read($brLittle));
        $this->assertEquals(65535, $this->int16->read($brLittle));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testSignedReaderWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->setPosition(12);
        $this->assertEquals(-1, $this->int16->readSigned($brLittle));
        $this->assertEquals(65535, $this->int16->read($brLittle));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBitReaderWithBigEndian($brBig, $brLittle)
    {
        $brBig->setPosition(6);
        $brBig->readBits(4);
        $this->assertEquals(1861, $this->int16->read($brBig));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBitReaderWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->setPosition(6);
        $brLittle->readBits(4);
        $this->assertEquals(1876, $this->int16->read($brLittle));
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian($brBig, $brLittle)
    {
        $brBig->readBits(128);
        $this->int16->read($brBig);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian($brBig, $brLittle)
    {
        $brLittle->readBits(128);
        $this->int16->read($brLittle);
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testAlternateMachineByteOrderSigned($brBig, $brLittle)
    {
        $brLittle->setMachineByteOrder(Endian::ENDIAN_BIG);
        $brLittle->setEndian(Endian::ENDIAN_LITTLE);
        $this->assertEquals(3, $this->int16->readSigned($brLittle));
    }

    public function testEndian()
    {
        $this->int16->setEndianBig('X');
        $this->assertEquals('X', $this->int16->getEndianBig());

        $this->int16->setEndianLittle('Y');
        $this->assertEquals('Y', $this->int16->getEndianLittle());
    }
}
