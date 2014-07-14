<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int16
 */
class Int16Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BinaryReader
     */
    public $brBig;

    /**
     * @var BinaryReader
     */
    public $brLittle;

    public function setUp()
    {
        $dataBig = file_get_contents(__DIR__ . '/../asset/testfile-big.bin');
        $dataLittle = file_get_contents(__DIR__ . '/../asset/testfile-little.bin');

        $this->brBig = new BinaryReader($dataBig, Endian::ENDIAN_BIG);
        $this->brLittle = new BinaryReader($dataLittle, Endian::ENDIAN_LITTLE);
    }

    public function testUnsignedReaderWithBigEndian()
    {
        $this->assertEquals(0, Int16::read($this->brBig));
        $this->assertEquals(3, Int16::read($this->brBig));
        $this->assertEquals(2, Int16::read($this->brBig));
        $this->assertEquals(26484, Int16::read($this->brBig));
        $this->assertEquals(25971, Int16::read($this->brBig));
        $this->assertEquals(29729, Int16::read($this->brBig));
        $this->assertEquals(65535, Int16::read($this->brBig));
        $this->assertEquals(65535, Int16::read($this->brBig));
    }

    public function testSignedReaderWithBigEndian()
    {
        $this->brBig->setPosition(12);
        $this->assertEquals(-1, Int16::readSigned($this->brBig));
        $this->assertEquals(65535, Int16::read($this->brBig));
    }

    public function testReaderWithLittleEndian()
    {
        $this->assertEquals(3, Int16::read($this->brLittle));
        $this->assertEquals(0, Int16::read($this->brLittle));
        $this->assertEquals(2, Int16::read($this->brLittle));
        $this->assertEquals(29799, Int16::read($this->brLittle));
        $this->assertEquals(29541, Int16::read($this->brLittle));
        $this->assertEquals(8564, Int16::read($this->brLittle));
        $this->assertEquals(65535, Int16::read($this->brLittle));
        $this->assertEquals(65535, Int16::read($this->brLittle));
    }

    public function testSignedReaderWithLittleEndian()
    {
        $this->brLittle->setPosition(12);
        $this->assertEquals(-1, Int16::readSigned($this->brLittle));
        $this->assertEquals(65535, Int16::read($this->brLittle));
    }

    public function testBitReaderWithBigEndian()
    {
        $this->brBig->setPosition(6);
        $this->brBig->readBits(4);
        $this->assertEquals(1861, Int16::read($this->brBig));
    }

    public function testBitReaderWithLittleEndian()
    {
        $this->brLittle->setPosition(6);
        $this->brLittle->readBits(4);
        $this->assertEquals(1876, Int16::read($this->brLittle));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian()
    {
        $this->brBig->readBits(128);
        Int16::read($this->brBig);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian()
    {
        $this->brLittle->readBits(128);
        Int16::read($this->brLittle);
    }

    public function testAlternateMachineByteOrderSigned()
    {
        $this->brLittle->setMachineByteOrder(Endian::ENDIAN_BIG);
        $this->brLittle->setEndian(Endian::ENDIAN_LITTLE);
        $this->assertEquals(3, Int16::readSigned($this->brLittle));
    }
}
