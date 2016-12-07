<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;
use PhpBinaryReader\Endian;

class Int64 implements TypeInterface
{
    /**
     * @var string
     */
    private $endianBig = 'N';

    /**
     * @var string
     */
    private $endianLittle = 'V';

    /**
     * Returns an Unsigned 64-bit Integer
     *
     * @param  \PhpBinaryReader\BinaryReader $br
     * @param  null                          $length
     * @return int
     * @throws \OutOfBoundsException
     */
    public function read(BinaryReader &$br, $length = null)
    {
        if (!$br->canReadBytes(8)) {
            throw new \OutOfBoundsException('Cannot read 64-bit int, it exceeds the boundary of the file');
        }

        $endian = $br->getEndian() == Endian::ENDIAN_BIG ? $this->endianBig : $this->endianLittle;
        $firstSegment = $br->readFromHandle(4);
        $secondSegment = $br->readFromHandle(4);

        $firstHalf = unpack($endian, $firstSegment)[1];
        $secondHalf = unpack($endian, $secondSegment)[1];

        if ($br->getEndian() == Endian::ENDIAN_BIG) {
            $value = bcadd($secondHalf, bcmul($firstHalf, "4294967296"));
        } else {
            $value = bcadd($firstHalf, bcmul($secondHalf, "4294967296"));
        }

        if ($br->getCurrentBit() != 0) {
            $value = $this->bitReader($br, $value);
        }

        return $value;
    }

    /**
     * Returns a Signed 64-Bit Integer
     *
     * @param  \PhpBinaryReader\BinaryReader $br
     * @return int
     */
    public function readSigned(&$br)
    {
        $value = $this->read($br);
        if (bccomp($value, bcpow(2, 63)) >= 0) {
            $value = bcsub($value, bcpow(2, 64));
        }

        return $value;
    }

    /**
     * @param  \PhpBinaryReader\BinaryReader $br
     * @param  int                           $data
     * @return int
     */
    private function bitReader(&$br, $data)
    {
        $bitmask = new BitMask();
        $loMask = $bitmask->getMask($br->getCurrentBit(), BitMask::MASK_LO);
        $hiMask = $bitmask->getMask($br->getCurrentBit(), BitMask::MASK_HI);
        $hiBits = ($br->getNextByte() & $hiMask) << 56;
        $miBits = ($data & 0xFFFFFFFFFFFFFF00) >> (8 - $br->getCurrentBit());
        $loBits = ($data & $loMask);
        $br->setNextByte($data & 0xFF);

        return $hiBits | $miBits | $loBits;
    }

    /**
     * @param string $endianBig
     */
    public function setEndianBig($endianBig)
    {
        $this->endianBig = $endianBig;
    }

    /**
     * @return string
     */
    public function getEndianBig()
    {
        return $this->endianBig;
    }

    /**
     * @param string $endianLittle
     */
    public function setEndianLittle($endianLittle)
    {
        $this->endianLittle = $endianLittle;
    }

    /**
     * @return string
     */
    public function getEndianLittle()
    {
        return $this->endianLittle;
    }
}
