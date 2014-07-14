<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;
use PhpBinaryReader\Endian;

class Int8 implements TypeInterface
{
    /**
     * @var string
     */
    private static $endian = 'C';

    /**
     * Returns an Unsigned 8-bit Integer (aka a single byte)
     *
     * @param  \PhpBinaryReader\BinaryReader $br
     * @param  null                          $length
     * @return int
     * @throws \OutOfBoundsException
     */
    public static function read(BinaryReader &$br, $length = null)
    {
        if (($br->getPosition() + 1) > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read 32-bit int, it exceeds the boundary of the file');
        }

        $segment = substr($br->getInputString(), $br->getPosition(), 1);

        $data = unpack(self::$endian, $segment);
        $data = $data[1];

        $br->setPosition($br->getPosition() + 1);

        if ($br->getCurrentBit() != 0) {
            $data = self::bitReader($br, $data);
        }

        return $data;
    }

    /**
     * Returns a Signed 8-bit Integer (aka a single byte)
     *
     * @param  \PhpBinaryReader\BinaryReader $br
     * @return int
     */
    public static function readSigned(&$br)
    {
        self::$endian = 'c';
        $value = self::read($br);
        self::$endian = 'C';

        return $value;
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
        $hiBits = $br->getNextByte() & $hiMask;
        $loBits = $data & $loMask;
        $br->setNextByte($data);

        return $hiBits | $loBits;
    }
}
