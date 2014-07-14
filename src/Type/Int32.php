<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;
use PhpBinaryReader\Endian;

class Int32 implements TypeInterface
{
    /**
     * @var string
     */
    private static $endianBig = 'N';

    /**
     * @var string
     */
    private static $endianLittle = 'V';

    /**
     * Returns an Unsigned 32-bit Integer
     *
     * @param  \PhpBinaryReader\BinaryReader $br
     * @param  null                          $length
     * @return int
     * @throws \OutOfBoundsException
     */
    public static function read(BinaryReader &$br, $length = null)
    {
        if (($br->getPosition() + 4) > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read 32-bit int, it exceeds the boundary of the file');
        }

        $endian = $br->getEndian() == Endian::ENDIAN_BIG ? self::$endianBig : self::$endianLittle;
        $segment = substr($br->getInputString(), $br->getPosition(), 4);

        $data = unpack($endian, $segment);
        $data = $data[1];

        $br->setPosition($br->getPosition() + 4);

        if ($br->getCurrentBit() != 0) {
            $data = self::bitReader($br, $data);
        }

        return $data;
    }

    /**
     * Returns a Signed 32-Bit Integer
     *
     * @param  \PhpBinaryReader\BinaryReader $br
     * @return int
     */
    public static function readSigned(&$br)
    {
        self::$endianBig = 'l';
        self::$endianLittle = 'l';

        $value = self::read($br);

        self::$endianBig = 'N';
        self::$endianLittle = 'V';

        if ($br->getMachineByteOrder() != Endian::ENDIAN_LITTLE && $br->getEndian() == Endian::ENDIAN_LITTLE) {
            return Endian::convert($value);
        } else {
            return $value;
        }
    }

    /**
     * @param  \PhpBinaryReader\BinaryReader $br
     * @param  int                           $data
     * @return int
     */
    private static function bitReader(&$br, $data)
    {
        $loMask = BitMask::getMask($br->getCurrentBit(), BitMask::MASK_LO);
        $hiMask = BitMask::getMask($br->getCurrentBit(), BitMask::MASK_HI);
        $hiBits = ($br->getNextByte() & $hiMask) << 24;
        $miBits = ($data & 0xFFFFFF00) >> (8 - $br->getCurrentBit());
        $loBits = ($data & $loMask);
        $br->setNextByte($data & 0xFF);

        return $hiBits | $miBits | $loBits;
    }
}
