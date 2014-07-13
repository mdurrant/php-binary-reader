<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;
use PhpBinaryReader\Endian;

class Int16 implements TypeInterface
{
    /**
     * @var string
     */
    private static $endianBig = 'n';

    /**
     * @var string
     */
    private static $endianLittle = 'v';

    /**
     * Returns an Unsigned 16-bit Integer
     *
     * @param \PhpBinaryReader\BinaryReader $br
     * @param null $length
     * @return int
     * @throws \OutOfBoundsException
     */
    public static function read(BinaryReader &$br, $length = null)
    {
        if (($br->getPosition() + 2) > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read 32-bit int, it exceeds the boundary of the file');
        }

        $endian = $br->getEndian() == Endian::ENDIAN_BIG ? self::$endianBig : self::$endianLittle;
        $segment = substr($br->getInputString(), $br->getPosition(), 2);

        $data = unpack($endian, $segment);
        $data = $data[1];

        $br->setPosition($br->getPosition() + 2);

        if ($br->getCurrentBit() != 0) {
            $data = self::bitReader($br, $data);
        }

        return $data;
    }

    /**
     * Returns a Signed 16-bit Integer
     *
     * @param \PhpBinaryReader\BinaryReader $br
     * @return int
     */
    public static function readSigned(&$br)
    {
        self::$endianBig = 's';
        self::$endianLittle = 's';

        $value = self::read($br);

        if ($br->getEndian() == Endian::ENDIAN_LITTLE && $br->getMachineByteOrder() == Endian::ENDIAN_LITTLE) {
            return $value;
        } elseif ($br->getEndian() == Endian::ENDIAN_BIG && $br->getMachineByteOrder() == Endian::ENDIAN_BIG) {
            return $value;
        } else {
            return Endian::convert($value);
        }
    }

    /**
     * @param \PhpBinaryReader\BinaryReader $br
     * @param int $data
     * @return int
     */
    private static function bitReader(&$br, $data)
    {
        $loMask = BitMask::getMask($br->getCurrentBit(), BitMask::MASK_LO);
        $hiMask = BitMask::getMask($br->getCurrentBit(), BitMask::MASK_HI);
        $hiBits = ($br->getNextByte() & $hiMask) << 8;
        $miBits = ($data & 0xFF00) >> (8 - $br->getCurrentBit());
        $loBits = ($data & $loMask);
        $br->setNextByte($data & 0xFF);

        return $hiBits | $miBits | $loBits;
    }
}
