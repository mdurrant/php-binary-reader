<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\String
 */
class StringTest extends \PHPUnit_Framework_TestCase
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

    public function testStringIsRead()
    {
        $this->brBig->setPosition(7);
        $this->brLittle->setPosition(7);

        $this->assertEquals('test!', String::read($this->brBig, 5));
        $this->assertEquals('test!', String::read($this->brLittle, 5));
    }

    public function testAlignedStringIsRead()
    {
        $this->brBig->setPosition(6);
        $this->brLittle->setPosition(6);
        $this->brBig->readBits(1);
        $this->brLittle->readBits(1);

        $this->assertEquals('test!', String::readAligned($this->brBig, 5));
        $this->assertEquals('test!', String::readAligned($this->brLittle, 5));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testExceptionIsThrownIfOutOfBoundsBigEndian()
    {
        $this->brBig->readBits(128);
        String::read($this->brBig, 1);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testExceptionIsThrownIfOutOfBoundsLittleEndian()
    {
        $this->brLittle->readBits(128);
        String::read($this->brLittle, 1);
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionIsThrownIfLengthIsInvalidBigEndian()
    {
        String::read($this->brBig, 'foo');
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionIsThrownIfLengthIsInvalidLittleEndian()
    {
        String::read($this->brBig, 'foo');
    }
}
