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
        $dataBig = fopen(__DIR__ . '/asset/testfile-big.bin', 'rb');
        $dataLittle = fopen(__DIR__ . '/asset/testfile-little.bin', 'rb');

        $this->brBig = new BinaryReader($dataBig, Endian::ENDIAN_BIG);
        $this->brLittle = new BinaryReader($dataLittle, Endian::ENDIAN_LITTLE);
    }

    public function testEof()
    {
        $this->brBig->setPosition(15);
        $this->assertFalse($this->brBig->isEof());
        $this->brBig->setPosition(16);
        $this->assertTrue($this->brBig->isEof());

        $this->brLittle->setPosition(15);
        $this->assertFalse($this->brLittle->isEof());
        $this->brLittle->setPosition(16);
        $this->assertTrue($this->brLittle->isEof());
    }

    public function testBitReader()
    {
        $this->assertEquals(50331648, $this->brBig->readBits(32));
        $this->assertEquals(3, $this->brLittle->readBits(32));

        $this->brBig->setPosition(0);
        $this->brLittle->setPosition(0);

        $this->assertEquals(3, $this->brBig->readUBits(32));
        $this->assertEquals(3, $this->brLittle->readUBits(32));
    }

    public function testInt8()
    {
        $this->brLittle->setPosition(6);
        $this->brBig->setPosition(6);

        $this->assertEquals(103, $this->brBig->readInt8());
        $this->assertEquals(103, $this->brLittle->readInt8());

        $this->brLittle->setPosition(6);
        $this->brBig->setPosition(6);

        $this->assertEquals(103, $this->brBig->readUInt8());
        $this->assertEquals(103, $this->brLittle->readUInt8());
    }

    public function testInt16()
    {
        $this->brLittle->setPosition(4);
        $this->brBig->setPosition(4);

        $this->assertEquals(512, $this->brBig->readInt16());
        $this->assertEquals(2, $this->brLittle->readInt16());

        $this->brLittle->setPosition(4);
        $this->brBig->setPosition(4);

        $this->assertEquals(2, $this->brBig->readUInt16());
        $this->assertEquals(2, $this->brLittle->readUInt16());
    }

    public function testInt32()
    {
        $this->assertEquals(50331648, $this->brBig->readInt32());
        $this->assertEquals(3, $this->brLittle->readInt32());

        $this->brLittle->setPosition(0);
        $this->brBig->setPosition(0);

        $this->assertEquals(3, $this->brBig->readUInt32());
        $this->assertEquals(3, $this->brLittle->readUInt32());
    }

    public function testAlign()
    {
        $this->brBig->readBits(30);
        $this->brLittle->readBits(30);

        $this->brBig->align();
        $this->brLittle->align();

        $this->assertEquals(0, $this->brBig->getCurrentBit());
        $this->assertEquals(0, $this->brLittle->getCurrentBit());
        $this->assertFalse($this->brBig->getNextByte());
        $this->assertFalse($this->brLittle->getNextByte());
        $this->assertEquals(2, $this->brBig->readUInt16());
        $this->assertEquals(2, $this->brLittle->readUInt16());
    }

    public function testBytes()
    {
        $this->brBig->setPosition(7);
        $this->brLittle->setPosition(7);

        $this->assertEquals('test!', $this->brBig->readBytes(5));
        $this->assertEquals('test!', $this->brLittle->readBytes(5));
    }

    public function testString()
    {
        $this->brBig->setPosition(7);
        $this->brLittle->setPosition(7);

        $this->assertEquals('test!', $this->brBig->readString(5));
        $this->assertEquals('test!', $this->brLittle->readString(5));
    }

    public function testAlignedString()
    {
        $this->brBig->setPosition(6);
        $this->brLittle->setPosition(6);

        $this->brBig->readBits(4);
        $this->brLittle->readBits(4);

        $this->assertEquals('test!', $this->brBig->readAlignedString(5));
        $this->assertEquals('test!', $this->brLittle->readAlignedString(5));
    }

    public function testEndianSet()
    {
        $this->brBig->setEndian(Endian::ENDIAN_LITTLE);
        $this->brLittle->setEndian(Endian::ENDIAN_BIG);

        $this->assertEquals(Endian::ENDIAN_LITTLE, $this->brBig->getEndian());
        $this->assertEquals(Endian::ENDIAN_BIG, $this->brLittle->getEndian());
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     */
    public function testExceptionIsThrownIfInvalidEndianSet()
    {
        $this->brBig->setEndian('foo');
    }

    public function testPositionSet()
    {
        $this->brBig->setPosition(5);
        $this->assertEquals(5, $this->brBig->getPosition());
    }

    public function testEofPosition()
    {
        $this->assertEquals(16, $this->brBig->getEofPosition());
        $this->assertEquals(16, $this->brLittle->getEofPosition());
    }

    public function testNextByte()
    {
        $this->brBig->readBits(70);
        $this->brLittle->readBits(70);

        $this->assertEquals(101, $this->brBig->getNextByte());
        $this->assertEquals(101, $this->brLittle->getNextByte());

        $this->brBig->setNextByte(5);
        $this->brLittle->setNextByte(5);

        $this->assertEquals(5, $this->brBig->getNextByte());
        $this->assertEquals(5, $this->brLittle->getNextByte());
    }

    public function testCurrentBit()
    {
        $this->assertEquals(0, $this->brBig->getCurrentBit());
        $this->assertEquals(0, $this->brLittle->getCurrentBit());

        $this->brBig->readBits(3);
        $this->brLittle->readBits(3);

        $this->assertEquals(3, $this->brBig->getCurrentBit());
        $this->assertEquals(3, $this->brLittle->getCurrentBit());

        $this->brBig->setCurrentBit(7);
        $this->brLittle->setCurrentBit(7);

        $this->assertEquals(7, $this->brBig->getCurrentBit());
        $this->assertEquals(7, $this->brLittle->getCurrentBit());
    }

    public function testInputString()
    {
        $this->brBig->setInputString('foo');
        $this->assertEquals('foo', $this->brBig->getInputString());
    }

    public function testInputHandle()
    {
        // Create a handle in-memory
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, 'Test');
        rewind($handle);

        $this->brBig->setInputHandle($handle);
        $this->assertEquals($handle, $this->brBig->getInputHandle());
    }

    public function testMachineByteOrder()
    {
        $this->assertEquals(Endian::ENDIAN_LITTLE, $this->brBig->getMachineByteOrder());
        $this->brBig->setMachineByteOrder(Endian::ENDIAN_BIG);
        $this->assertEquals(Endian::ENDIAN_BIG, $this->brBig->getMachineByteOrder());
    }
}
