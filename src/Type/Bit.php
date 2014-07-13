<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\BitMask;

class Bit implements TypeInterface
{
    /**
     * @var bool
     */
    private static $signed = false;

    /**
     * Returns an unsigned integer from the bit level
     *
     * @param \PhpBinaryReader\BinaryReader $br
     * @param int $length
     * @throws \OutOfBoundsException
     * @throws InvalidDataException
     * @return int
     */
    public static function read(BinaryReader &$br, $length)
    {
        if (!is_int($length)) {
            throw new InvalidDataException('The length parameter must be an integer');
        }

        if (($length / 8) + $br->getPosition() > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read bits, it exceeds the boundary of the file');
        }

        $result = 0;
        $bits = $length;
        $shift = $br->getCurrentBit();

        if ($shift != 0) {
            $bitsLeft = 8 - $shift;

            if ($bitsLeft < $bits) {
                $bits -= $bitsLeft;
                $result = ($br->getNextByte() >> $shift) << $bits;
            } elseif ($bitsLeft > $bits) {
                $br->setCurrentBit($br->getCurrentBit() + $bits);
                return ($br->getNextByte() >> $shift) & BitMask::getMask($bits, BitMask::MASK_LO);
            } else {
                $br->setCurrentBit(0);
                return $br->getNextByte() >> $shift;
            }
        }

        if ($bits >= 8) {
            $bytes = intval($bits / 8);

            if ($bytes == 1) {
                $bits -= 8;
                $result |= (self::$signed ? $br->readInt8() : $br->readUInt8()) << $bits;
            } elseif ($bytes == 2) {
                $bits -= 16;
                $result |= (self::$signed ? $br->readInt16() : $br->readUInt16()) << $bits;
            } elseif ($bytes == 4) {
                $bits -= 32;
                $result |= (self::$signed ? $br->readInt32() : $br->readUInt32()) << $bits;
            } else {
                while ($bits > 8) {
                    $bits -= 8;
                    $result |= (self::$signed ? $br->readInt8() : $br->readUInt8()) << 8;
                }
            }
        }

        if ($bits != 0) {
            $code = self::$signed ? 'c' : 'C';
            $data = unpack($code, substr($br->getInputString(), $br->getPosition(), 1));
            $br->setNextByte($data[1]);
            $br->setPosition($br->getPosition() + 1);
            $result |= $br->getNextByte() & BitMask::getMask($bits, BitMask::MASK_LO);
        }

        $br->setCurrentBit($bits);

        return $result;
    }

    /**
     * Returns a signed integer from the bit level
     *
     * @param \PhpBinaryReader\BinaryReader $br
     * @param int $length
     * @return int
     */
    public static function readSigned(&$br, $length)
    {
        self::$signed = true;
        return self::read($br, $length);
    }
}
