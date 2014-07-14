<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int32
 */
class Int32Test extends \PHPUnit_Framework_TestCase
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
        $this->assertEquals(3, Int32::read($this->brBig));
        $this->assertEquals(157556, Int32::read($this->brBig));
        $this->assertEquals(1702065185, Int32::read($this->brBig));
        $this->assertEquals(4294967295, Int32::read($this->brBig));
    }

    public function testSignedReaderWithBigEndian()
    {
        $this->brBig->setPosition(12);
        $this->assertEquals(-1, Int32::readSigned($this->brBig));
    }

    public function testReaderWithLittleEndian()
    {
        $this->assertEquals(3, Int32::read($this->brLittle));
        $this->assertEquals(1952907266, Int32::read($this->brLittle));
        $this->assertEquals(561279845, Int32::read($this->brLittle));
        $this->assertEquals(4294967295, Int32::read($this->brLittle));
    }

    public function testSignedReaderWithLittleEndian()
    {
        $this->brLittle->setPosition(12);
        $this->assertEquals(-1, Int32::readSigned($this->brLittle));
    }

    public function testBitReaderWithBigEndian()
    {
        $this->brBig->setPosition(6);
        $this->brBig->readBits(4);
        $this->assertEquals(122050356, Int32::read($this->brBig));
    }

    public function testBitReaderWithLittleEndian()
    {
        $this->brLittle->setPosition(6);
        $this->brLittle->readBits(4);
        $this->assertEquals(122107476, Int32::read($this->brLittle));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian()
    {
        $this->brBig->readBits(128);
        Int32::read($this->brBig);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian()
    {
        $this->brLittle->readBits(128);
        Int32::read($this->brLittle);
    }

    public function testAlternateMachineByteOrderSigned()
    {
        $this->brLittle->setMachineByteOrder(Endian::ENDIAN_BIG);
        $this->brLittle->setEndian(Endian::ENDIAN_LITTLE);
        $this->assertEquals(3, Int32::readSigned($this->brLittle));
    }
}
