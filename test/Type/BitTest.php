<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Bit
 */
class BitTest extends \PHPUnit_Framework_TestCase
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

    public function testUnsignedBitReader()
    {
        $this->assertEquals(3, $this->brBig->readUBits(32));
        $this->assertEquals(3, $this->brLittle->readUBits(32));
        $this->assertEquals(2, $this->brBig->readUBits(16));
        $this->assertEquals(2, $this->brLittle->readUBits(16));
        $this->assertEquals(103, $this->brBig->readUBits(8));
        $this->assertEquals(103, $this->brLittle->readUBits(8));

        $this->brBig->setPosition(0);
        $this->brLittle->setPosition(0);

        $this->brBig->readUBits(28);
        $this->brLittle->readUBits(28);

        $this->assertEquals(0, $this->brBig->readUBits(6));
        $this->assertEquals(2, $this->brLittle->readUBits(6));
        $this->assertEquals(0, $this->brBig->readUBits(4));
        $this->assertEquals(0, $this->brLittle->readUBits(4));
        $this->assertEquals(0, $this->brBig->readUBits(2));
        $this->assertEquals(0, $this->brLittle->readUBits(2));
    }

    public function testSignedBitReader()
    {
        $this->assertEquals(50331648, $this->brBig->readBits(32));
        $this->assertEquals(3, $this->brLittle->readBits(32));
        $this->assertEquals(512, $this->brBig->readBits(16));
        $this->assertEquals(2, $this->brLittle->readBits(16));
        $this->assertEquals(103, $this->brBig->readBits(8));
        $this->assertEquals(103, $this->brLittle->readBits(8));

        $this->brBig->setPosition(0);
        $this->brLittle->setPosition(0);

        $this->brBig->readBits(28);
        $this->brLittle->readBits(28);

        $this->assertEquals(0, $this->brBig->readBits(6));
        $this->assertEquals(2, $this->brLittle->readBits(6));
        $this->assertEquals(0, $this->brBig->readBits(4));
        $this->assertEquals(0, $this->brLittle->readBits(4));
        $this->assertEquals(0, $this->brBig->readBits(2));
        $this->assertEquals(0, $this->brLittle->readBits(2));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testExceptionBitsBigEndian()
    {
        $this->brBig->setPosition(16);
        $this->brBig->readBits(16);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testExceptionBitsLittleEndian()
    {
        $this->brLittle->setPosition(16);
        $this->brLittle->readBits(16);
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionInvalidBitCountBigEndian()
    {
        $this->brBig->readBits('foo');
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionInvalidBitCountLittleEndian()
    {
        $this->brLittle->readBits('foo');
    }
}
