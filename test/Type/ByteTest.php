<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Endian;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Byte
 */
class ByteTest extends AbstractTestCase
{
    /**
     * @var Byte
     */
    public $byte;

    public function setUp()
    {
        $this->byte = new Byte();
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBytesAreRead($brBig, $brLittle)
    {
        $brBig->setPosition(7);
        $brLittle->setPosition(7);

        $this->assertEquals('test!', $this->byte->read($brBig, 5));
        $this->assertEquals('test!', $this->byte->read($brLittle, 5));
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testExceptionIsThrownIfOutOfBoundsBigEndian($brBig, $brLittle)
    {
        $brBig->readBits(128);
        $this->byte->read($brBig, 1);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testExceptionIsThrownIfOutOfBoundsLittleEndian($brBig, $brLittle)
    {
        $brLittle->readBits(128);
        $this->byte->read($brLittle, 1);
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     * @dataProvider binaryReaders
     */
    public function testExceptionIsThrownIfLengthIsInvalidBigEndian($brBig, $brLittle)
    {
        $this->byte->read($brBig, 'foo');
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     * @dataProvider binaryReaders
     */
    public function testExceptionIsThrownIfLengthIsInvalidLittleEndian($brBig, $brLittle)
    {
        $this->byte->read($brBig, 'foo');
    }
}
