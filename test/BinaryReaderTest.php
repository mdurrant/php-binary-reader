<?php

namespace PhpBinaryReader;

/**
 * @coversDefaultClass \PhpBinaryReader\BinaryReader
 */
class BinaryReaderTest extends \PHPUnit_Framework_TestCase
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
        $this->brBig = new BinaryReader(file_get_contents(__DIR__ . '/asset/testfile-big.bin'), 'big');
        $this->brLittle = new BinaryReader(file_get_contents(__DIR__ . '/asset/testfile-little.bin'), 'little');
    }

    /**
     * @covers ::_construct, ::getEofPosition, ::getEndian
     */
    public function testConstructor()
    {
        $this->assertEquals(12, $this->brBig->getEofPosition());
        $this->assertEquals(12, $this->brLittle->getEofPosition());
        $this->assertEquals('big', $this->brBig->getEndian());
        $this->assertEquals('little', $this->brLittle->getEndian());
    }

    /**
     * @covers ::readUInt32
     */
    public function test32BitInteger()
    {
        $this->assertEquals(3, $this->brBig->readUInt32());
        $this->assertEquals(3, $this->brLittle->readUInt32());
    }

    /**
     * @covers ::readUInt16
     */
    public function test16BitInteger()
    {
        $this->brBig->readUInt32();
        $this->brLittle->readUInt32();

        $this->assertEquals(2, $this->brBig->readUInt16());
        $this->assertEquals(2, $this->brLittle->readUInt16());
    }

    /**
     * @covers ::readUInt8
     */
    public function test8BitInteger()
    {
        $this->brBig->readUInt32();
        $this->brLittle->readUInt32();
        $this->brBig->readUInt16();
        $this->brLittle->readUInt16();

        $this->assertEquals(103, $this->brBig->readUInt8());
        $this->assertEquals(103, $this->brLittle->readUInt8());
    }

    /**
     * @covers ::readString
     */
    public function testString()
    {
        $this->brBig->readUInt32();
        $this->brLittle->readUInt32();
        $this->brBig->readUInt16();
        $this->brLittle->readUInt16();
        $this->brBig->readUInt8();
        $this->brLittle->readUInt8();

        $this->assertEquals('test!', $this->brBig->readString(5));
        $this->assertEquals('test!', $this->brLittle->readString(5));
    }

    /**
     * @covers ::readBits
     */
    public function testBitReader()
    {
        $this->assertEquals(3, $this->brBig->readBits(32));
        $this->assertEquals(3, $this->brLittle->readBits(32));
        $this->assertEquals(2, $this->brBig->readBits(16));
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

    public function test32BitWithNonZeroCurrentBit()
    {
        $this->brBig->readBits(2);
        $this->brLittle->readBits(2);
        $this->assertEquals(12, $this->brBig->readUInt32());
        $this->assertEquals(524288, $this->brLittle->readUInt32());
    }

    public function test16BitWithNonZeroCurrentBit()
    {
        $this->brBig->readBits(2);
        $this->brLittle->readBits(2);
        $this->assertEquals(0, $this->brBig->readUInt16());
        $this->assertEquals(0, $this->brLittle->readUInt16());
    }

    public function test8BitWithNonZeroCurrentBit()
    {
        $this->brBig->readBits(2);
        $this->brLittle->readBits(2);
        $this->assertEquals(0, $this->brBig->readUInt8());
        $this->assertEquals(0, $this->brLittle->readUInt8());
    }

    /**
     * @covers ::readBytes
     */
    public function testByteReader()
    {
        $this->assertEquals(3, $this->brBig->readBytes(4));
        $this->assertEquals(3, $this->brLittle->readBytes(4));
        $this->assertEquals(2, $this->brBig->readBytes(2));
        $this->assertEquals(2, $this->brLittle->readBytes(2));
        $this->assertEquals(103, $this->brBig->readBytes(1));
        $this->assertEquals(103, $this->brLittle->readBytes(1));
    }

    /**
     * @covers ::align, ::getPosition
     */
    public function testAlign()
    {
        $this->brBig->readBits(1);
        $this->brBig->align(true);
        $this->brBig->readBits(1);
        $this->brBig->align(true);
        $this->assertEquals(4, $this->brBig->getPosition());

        $this->brLittle->readBits(1);
        $this->brLittle->align(true);
        $this->brLittle->readBits(1);
        $this->brLittle->align(true);
        $this->assertEquals(4, $this->brLittle->getPosition());

        $this->assertEquals(2, $this->brBig->readBits(16));
        $this->assertEquals(2, $this->brLittle->readBits(16));
    }

    /**
     * @covers ::isEof
     */
    public function testEof()
    {
        $this->assertFalse($this->brBig->isEof());
        $this->assertFalse($this->brLittle->isEof());

        $this->brBig->readUInt32();
        $this->brLittle->readUInt32();
        $this->brBig->readUInt16();
        $this->brLittle->readUInt16();
        $this->brBig->readUInt8();
        $this->brLittle->readUInt8();
        $this->brBig->readString(5);
        $this->brLittle->readString(5);

        $this->assertTrue($this->brBig->isEof());
        $this->assertTrue($this->brLittle->isEof());
    }

    /**
     * @covers ::getCurrentBit
     */
    public function testCurrentBit()
    {
        $this->brBig->readBits(2);
        $this->brLittle->readBits(2);

        $this->assertEquals(2, $this->brBig->getCurrentBit());
        $this->assertEquals(2, $this->brLittle->getCurrentBit());
    }

    /**
     * @covers ::setPosition
     */
    public function testPositionSet()
    {
        $this->brBig->setPosition(4);
        $this->brLittle->setPosition(4);

        $this->assertEquals(2, $this->brBig->readUInt16());
        $this->assertEquals(2, $this->brLittle->readUInt16());

        $this->brBig->setPosition(0);
        $this->brLittle->setPosition(0);

        $this->assertEquals(3, $this->brBig->readUInt32());
        $this->assertEquals(3, $this->brLittle->readUInt32());
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testException32Bit()
    {
        $this->brBig->setPosition(12);
        $this->brBig->readUInt32();
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testException16Bit()
    {
        $this->brBig->setPosition(12);
        $this->brBig->readUInt16();
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testException8Bit()
    {
        $this->brBig->setPosition(12);
        $this->brBig->readUInt8();
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testExceptionString()
    {
        $this->brBig->setPosition(12);
        $this->brBig->readString(1);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testExceptionBits()
    {
        $this->brBig->setPosition(12);
        $this->brBig->readBits(16);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionBadEndian()
    {
        $test = new BinaryReader('string', 'bigendian');
    }
}
