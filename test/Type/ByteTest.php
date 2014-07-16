<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Byte
 */
class ByteTest extends \PHPUnit_Framework_TestCase
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
     * @var Byte
     */
    public $byte;

    public function setUp()
    {
        $dataBig = file_get_contents(__DIR__ . '/../asset/testfile-big.bin');
        $dataLittle = file_get_contents(__DIR__ . '/../asset/testfile-little.bin');

        $this->byte = new Byte();
        $this->brBig = new BinaryReader($dataBig, Endian::ENDIAN_BIG);
        $this->brLittle = new BinaryReader($dataLittle, Endian::ENDIAN_LITTLE);
    }

    public function testBytesAreRead()
    {
        $this->brBig->setPosition(7);
        $this->brLittle->setPosition(7);

        $this->assertEquals('test!', $this->byte->read($this->brBig, 5));
        $this->assertEquals('test!', $this->byte->read($this->brLittle, 5));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testExceptionIsThrownIfOutOfBoundsBigEndian()
    {
        $this->brBig->readBits(128);
        $this->byte->read($this->brBig, 1);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testExceptionIsThrownIfOutOfBoundsLittleEndian()
    {
        $this->brLittle->readBits(128);
        $this->byte->read($this->brLittle, 1);
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionIsThrownIfLengthIsInvalidBigEndian()
    {
        $this->byte->read($this->brBig, 'foo');
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionIsThrownIfLengthIsInvalidLittleEndian()
    {
        $this->byte->read($this->brBig, 'foo');
    }
}
