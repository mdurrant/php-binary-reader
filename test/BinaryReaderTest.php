<?php

namespace PhpBinaryReader;

/**
 * @coversDefaultClass \PhpBinaryReader\BinaryReader
 */
class BinaryReaderTest extends AbstractTestCase
{
    public function setUp()
    {
        $dataBig = fopen(__DIR__ . '/asset/testfile-big.bin', 'rb');
        $dataLittle = fopen(__DIR__ . '/asset/testfile-little.bin', 'rb');

        $brBig = new BinaryReader($dataBig, Endian::ENDIAN_BIG);
        $brLittle = new BinaryReader($dataLittle, Endian::ENDIAN_LITTLE);
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testEof($brBig, $brLittle)
    {
        $brBig->setPosition(15);
        $this->assertFalse($brBig->isEof());
        $brBig->setPosition(16);
        $this->assertTrue($brBig->isEof());

        $brLittle->setPosition(15);
        $this->assertFalse($brLittle->isEof());
        $brLittle->setPosition(16);
        $this->assertTrue($brLittle->isEof());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testCanReadBytes($brBig, $brLittle)
    {
        $brBig->setPosition(15);
        $this->assertTrue($brBig->canReadBytes());
        $this->assertTrue($brBig->canReadBytes(1));
        $this->assertFalse($brBig->canReadBytes(2));

        $brLittle->setPosition(15);
        $this->assertTrue($brLittle->canReadBytes());
        $this->assertTrue($brLittle->canReadBytes(1));
        $this->assertFalse($brLittle->canReadBytes(2));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBitReader($brBig, $brLittle)
    {
        $this->assertEquals(50331648, $brBig->readBits(32));
        $this->assertEquals(3, $brLittle->readBits(32));

        $brBig->setPosition(0);
        $brLittle->setPosition(0);

        $this->assertEquals(3, $brBig->readUBits(32));
        $this->assertEquals(3, $brLittle->readUBits(32));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testInt8($brBig, $brLittle)
    {
        $brLittle->setPosition(6);
        $brBig->setPosition(6);

        $this->assertEquals(103, $brBig->readInt8());
        $this->assertEquals(103, $brLittle->readInt8());

        $brLittle->setPosition(6);
        $brBig->setPosition(6);

        $this->assertEquals(103, $brBig->readUInt8());
        $this->assertEquals(103, $brLittle->readUInt8());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testInt16($brBig, $brLittle)
    {
        $brLittle->setPosition(4);
        $brBig->setPosition(4);

        $this->assertEquals(512, $brBig->readInt16());
        $this->assertEquals(2, $brLittle->readInt16());

        $brLittle->setPosition(4);
        $brBig->setPosition(4);

        $this->assertEquals(2, $brBig->readUInt16());
        $this->assertEquals(2, $brLittle->readUInt16());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testInt32($brBig, $brLittle)
    {
        $this->assertEquals(50331648, $brBig->readInt32());
        $this->assertEquals(3, $brLittle->readInt32());

        $brLittle->setPosition(0);
        $brBig->setPosition(0);

        $this->assertEquals(3, $brBig->readUInt32());
        $this->assertEquals(3, $brLittle->readUInt32());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testAlign($brBig, $brLittle)
    {
        $brBig->readBits(30);
        $brLittle->readBits(30);

        $brBig->align();
        $brLittle->align();

        $this->assertEquals(0, $brBig->getCurrentBit());
        $this->assertEquals(0, $brLittle->getCurrentBit());
        $this->assertFalse($brBig->getNextByte());
        $this->assertFalse($brLittle->getNextByte());
        $this->assertEquals(2, $brBig->readUInt16());
        $this->assertEquals(2, $brLittle->readUInt16());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testBytes($brBig, $brLittle)
    {
        $brBig->setPosition(7);
        $brLittle->setPosition(7);

        $this->assertEquals('test!', $brBig->readBytes(5));
        $this->assertEquals('test!', $brLittle->readBytes(5));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testString($brBig, $brLittle)
    {
        $brBig->setPosition(7);
        $brLittle->setPosition(7);

        $this->assertEquals('test!', $brBig->readString(5));
        $this->assertEquals('test!', $brLittle->readString(5));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testAlignedString($brBig, $brLittle)
    {
        $brBig->setPosition(6);
        $brLittle->setPosition(6);

        $brBig->readBits(4);
        $brLittle->readBits(4);

        $this->assertEquals('test!', $brBig->readAlignedString(5));
        $this->assertEquals('test!', $brLittle->readAlignedString(5));
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testEndianSet($brBig, $brLittle)
    {
        $brBig->setEndian(Endian::ENDIAN_LITTLE);
        $brLittle->setEndian(Endian::ENDIAN_BIG);

        $this->assertEquals(Endian::ENDIAN_LITTLE, $brBig->getEndian());
        $this->assertEquals(Endian::ENDIAN_BIG, $brLittle->getEndian());
    }

    /**
     * @expectedException \PhpBinaryReader\Exception\InvalidDataException
     * @dataProvider binaryReaders
     */
    public function testExceptionIsThrownIfInvalidEndianSet($brBig, $brLittle)
    {
        $brBig->setEndian('foo');
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testPositionSet($brBig, $brLittle)
    {
        $brBig->setPosition(5);
        $this->assertEquals(5, $brBig->getPosition());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testEofPosition($brBig, $brLittle)
    {
        $this->assertEquals(16, $brBig->getEofPosition());
        $this->assertEquals(16, $brLittle->getEofPosition());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testNextByte($brBig, $brLittle)
    {
        $brBig->readBits(70);
        $brLittle->readBits(70);

        $this->assertEquals(101, $brBig->getNextByte());
        $this->assertEquals(101, $brLittle->getNextByte());

        $brBig->setNextByte(5);
        $brLittle->setNextByte(5);

        $this->assertEquals(5, $brBig->getNextByte());
        $this->assertEquals(5, $brLittle->getNextByte());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testCurrentBit($brBig, $brLittle)
    {
        $this->assertEquals(0, $brBig->getCurrentBit());
        $this->assertEquals(0, $brLittle->getCurrentBit());

        $brBig->readBits(3);
        $brLittle->readBits(3);

        $this->assertEquals(3, $brBig->getCurrentBit());
        $this->assertEquals(3, $brLittle->getCurrentBit());

        $brBig->setCurrentBit(7);
        $brLittle->setCurrentBit(7);

        $this->assertEquals(7, $brBig->getCurrentBit());
        $this->assertEquals(7, $brLittle->getCurrentBit());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testInputString($brBig, $brLittle)
    {
        $brBig->setInputString('foo');
        $this->assertEquals('foo', $brBig->getInputString());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testInputHandle($brBig, $brLittle)
    {
        // Create a handle in-memory
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, 'Test');
        rewind($handle);

        $brBig->setInputHandle($handle);
        $this->assertEquals($handle, $brBig->getInputHandle());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testMachineByteOrder($brBig, $brLittle)
    {
        $this->assertEquals(Endian::ENDIAN_LITTLE, $brBig->getMachineByteOrder());
        $brBig->setMachineByteOrder(Endian::ENDIAN_BIG);
        $this->assertEquals(Endian::ENDIAN_BIG, $brBig->getMachineByteOrder());
    }

    /**
     * @dataProvider binaryReaders
     */
    public function testReadFromHandle($brBig, $brLittle)
    {
        $this->assertEquals('03000000', bin2hex($brLittle->readFromHandle(4)));
        $this->assertEquals(4, $brLittle->getPosition());

        $this->assertEquals("0x03", bin2hex($brBig->readFromHandle(4)));
        $this->assertEquals(4, $brBig->getPosition());
    }

    public function testReaders()
    {
        $dataBig = fopen(__DIR__ . '/asset/testfile-big.bin', 'rb');
        $brBig = new BinaryReader($dataBig, Endian::ENDIAN_BIG);
        $this->assertInstanceOf('\PhpBinaryReader\Type\Bit', $brBig->getBitReader());
        $this->assertInstanceOf('\PhpBinaryReader\Type\Byte', $brBig->getByteReader());
        $this->assertInstanceOf('\PhpBinaryReader\Type\Int16', $brBig->getInt16Reader());
        $this->assertInstanceOf('\PhpBinaryReader\Type\Int32', $brBig->getInt32Reader());
        $this->assertInstanceOf('\PhpBinaryReader\Type\Int8', $brBig->getInt8Reader());
        $this->assertInstanceOf('\PhpBinaryReader\Type\String', $brBig->getStringReader());
    }
}
