<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\AbstractTestCase;
use PhpBinaryReader\BinaryReader;

/**
 * @coversDefaultClass \PhpBinaryReader\Type\Single
 */
class SingleTest extends AbstractTestCase
{
    /**
     * @var Single
     */
    public $single;

    public function setUp()
    {
        $this->single = new Single();
    }

    /**
     * @param \PhpBinaryReader\BinaryReader $brBig
     * @param \PhpBinaryReader\BinaryReader $brLittle
     *
     * @dataProvider binaryReaders
     */
    public function testReaderWithLittleEndian(BinaryReader $brBig, BinaryReader $brLittle)
    {
        $brLittle->setPosition(16);
        $this->assertEquals(1.0, $this->single->read($brLittle));
        $this->assertEquals(-1.0, $this->single->read($brLittle));
        $this->assertTrue(is_nan($this->single->read($brLittle)));
        $this->assertSame(INF, $this->single->read($brLittle));
        $this->assertSame(-INF, $this->single->read($brLittle));
    }

    /**
     * @param \PhpBinaryReader\BinaryReader $brBig
     * @param \PhpBinaryReader\BinaryReader $brLittle
     *
     * @dataProvider binaryReaders
     */
    public function testReaderWithBigEndian(BinaryReader $brBig, BinaryReader $brLittle)
    {
        $brBig->setPosition(16);
        $this->assertEquals(1.0, $this->single->read($brBig));
        $this->assertEquals(-1.0, $this->single->read($brBig));
        $this->assertTrue(is_nan($this->single->read($brBig)));
        $this->assertSame(INF, $this->single->read($brBig));
        $this->assertSame(-INF, $this->single->read($brBig));
    }

    /**
     * @param \PhpBinaryReader\BinaryReader $brBig
     * @param \PhpBinaryReader\BinaryReader $brLittle
     *
     * @dataProvider binaryReaders
     */
    public function testBitReaderWithBigEndian(BinaryReader $brBig, BinaryReader $brLittle)
    {
        $brBig->setPosition(36);
        $brBig->readBits(1);
        $this->assertSame(1.0, $this->single->read($brBig));
        $this->assertSame(-1.0, $this->single->read($brBig));
    }

    /**
     * @param \PhpBinaryReader\BinaryReader $brBig
     * @param \PhpBinaryReader\BinaryReader $brLittle
     *
     * @dataProvider binaryReaders
     */
    public function testBitReaderWithLittleEndian(BinaryReader $brBig, BinaryReader $brLittle)
    {
        $brLittle->setPosition(36);
        $brLittle->readBits(1);
        $this->assertSame(1.0, $this->single->read($brLittle));
        $this->assertSame(-1.0, $this->single->read($brLittle));
    }

    /**
     * @param \PhpBinaryReader\BinaryReader $brBig
     * @param \PhpBinaryReader\BinaryReader $brLittle
     *
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testOutOfBoundsExceptionIsThrownWithBigEndian(BinaryReader $brBig, BinaryReader $brLittle)
    {
        $brBig->readBits(360);
        $this->single->read($brBig);
    }

    /**
     * @param \PhpBinaryReader\BinaryReader $brBig
     * @param \PhpBinaryReader\BinaryReader $brLittle
     *
     * @expectedException \OutOfBoundsException
     * @dataProvider binaryReaders
     */
    public function testOutOfBoundsExceptionIsThrownWithLittleEndian(BinaryReader $brBig, BinaryReader $brLittle)
    {
        $brLittle->readBits(360);
        $this->single->read($brLittle);
    }
}
