<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Int8
 */
class Int8Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BinaryReader
     */
    public $brBig;

    /**
     * @var BinaryReader
     */
    public $brLittle;

    /**
     * @var Int8
     */
    public $int8;

    public function setUp()
    {
        $dataBig = file_get_contents(__DIR__ . '/../asset/testfile-big.bin');
        $dataLittle = file_get_contents(__DIR__ . '/../asset/testfile-little.bin');

        $this->int8 = new Int8();
        $this->brBig = new BinaryReader($dataBig, Endian::ENDIAN_BIG);
        $this->brLittle = new BinaryReader($dataLittle, Endian::ENDIAN_LITTLE);
    }

    public function testUnsignedReaderWithBigEndian()
    {
        $this->assertEquals(0, $this->int8->read($this->brBig));
        $this->assertEquals(0, $this->int8->read($this->brBig));
        $this->assertEquals(0, $this->int8->read($this->brBig));
        $this->assertEquals(3, $this->int8->read($this->brBig));
        $this->assertEquals(0, $this->int8->read($this->brBig));
        $this->assertEquals(2, $this->int8->read($this->brBig));
        $this->assertEquals(103, $this->int8->read($this->brBig));
        $this->assertEquals(116, $this->int8->read($this->brBig));
        $this->assertEquals(101, $this->int8->read($this->brBig));
        $this->assertEquals(115, $this->int8->read($this->brBig));
        $this->assertEquals(116, $this->int8->read($this->brBig));
        $this->assertEquals(33, $this->int8->read($this->brBig));
        $this->assertEquals(255, $this->int8->read($this->brBig));
        $this->assertEquals(255, $this->int8->read($this->brBig));
        $this->assertEquals(255, $this->int8->read($this->brBig));
        $this->assertEquals(255, $this->int8->read($this->brBig));
    }

    public function testSignedReaderWithBigEndian()
    {
        $this->brBig->setPosition(12);
        $this->assertEquals(255, $this->int8->read($this->brBig));
        $this->assertEquals(-1, $this->int8->readSigned($this->brBig));
        $this->assertEquals(-1, $this->int8->readSigned($this->brBig));
        $this->assertEquals(255, $this->int8->read($this->brBig));
    }

    public function testReaderWithLittleEndian()
    {
        $this->assertEquals(3, $this->int8->read($this->brLittle));
        $this->assertEquals(0, $this->int8->read($this->brLittle));
        $this->assertEquals(0, $this->int8->read($this->brLittle));
        $this->assertEquals(0, $this->int8->read($this->brLittle));
        $this->assertEquals(2, $this->int8->read($this->brLittle));
        $this->assertEquals(0, $this->int8->read($this->brLittle));
        $this->assertEquals(103, $this->int8->read($this->brLittle));
        $this->assertEquals(116, $this->int8->read($this->brLittle));
        $this->assertEquals(101, $this->int8->read($this->brLittle));
        $this->assertEquals(115, $this->int8->read($this->brLittle));
        $this->assertEquals(116, $this->int8->read($this->brLittle));
        $this->assertEquals(33, $this->int8->read($this->brLittle));
        $this->assertEquals(255, $this->int8->read($this->brLittle));
        $this->assertEquals(255, $this->int8->read($this->brLittle));
        $this->assertEquals(255, $this->int8->read($this->brLittle));
        $this->assertEquals(255, $this->int8->read($this->brLittle));
    }

    public function testSignedReaderWithLittleEndian()
    {
        $this->brLittle->setPosition(12);
        $this->assertEquals(255, $this->int8->read($this->brLittle));
        $this->assertEquals(-1, $this->int8->readSigned($this->brLittle));
        $this->assertEquals(-1, $this->int8->readSigned($this->brLittle));
        $this->assertEquals(255, $this->int8->read($this->brLittle));
    }

    public function testBitReaderWithBigEndian()
    {
        $this->brBig->setPosition(6);
        $this->brBig->readBits(4);
        $this->assertEquals(7, $this->int8->read($this->brBig));
    }

    public function testBitReaderWithLittleEndian()
    {
        $this->brLittle->setPosition(6);
        $this->brLittle->readBits(4);
        $this->assertEquals(7, $this->int8->read($this->brLittle));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian()
    {
        $this->brBig->readBits(128);
        $this->int8->read($this->brBig);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian()
    {
        $this->brLittle->readBits(128);
        $this->int8->read($this->brLittle);
    }
}
