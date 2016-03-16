<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Str
 */
class StrTest extends AbstractTestCase
{
    /**
     * @var Str
     */
    public $string;

    public function setUp()
    {
        $this->string = new Str();
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testStringIsRead($brBig, $brLittle)
    {
        $brBig->setPosition(7);
        $brLittle->setPosition(7);

        $this->assertEquals('test!', $this->string->read($brBig, 5));
        $this->assertEquals('test!', $this->string->read($brLittle, 5));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testAlignedStringIsRead($brBig, $brLittle)
    {
        $brBig->setPosition(6);
        $brLittle->setPosition(6);
        $brBig->readBits(1);
        $brLittle->readBits(1);

        $this->assertEquals('test!', $this->string->readAligned($brBig, 5));
        $this->assertEquals('test!', $this->string->readAligned($brLittle, 5));
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testExceptionIsThrownIfOutOfBoundsBigEndian($brBig, $brLittle)
    {
        $brBig->readBits(128);
        $this->string->read($brBig, 1);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testExceptionIsThrownIfOutOfBoundsLittleEndian($brBig, $brLittle)
    {
        $brLittle->readBits(128);
        $this->string->read($brLittle, 1);
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     * @dataProvider binaryReaders
     */
    public function testExceptionIsThrownIfLengthIsInvalidBigEndian($brBig, $brLittle)
    {
        $this->string->read($brBig, 'foo');
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     * @dataProvider binaryReaders
     */
    public function testExceptionIsThrownIfLengthIsInvalidLittleEndian($brBig, $brLittle)
    {
        $this->string->read($brBig, 'foo');
    }
}
