<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Exception\InvalidDataException;

class String implements TypeInterface
{
    /**
     * @param  \PhpBinaryReader\BinaryReader $br
     * @param  int                           $length
     * @return string
     * @throws \OutOfBoundsException
     * @throws InvalidDataException
     */
    public static function read(BinaryReader &$br, $length)
    {
        if (!is_int($length)) {
            throw new InvalidDataException('The length parameter must be an integer');
        }

        if (($length + $br->getPosition()) > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read string, it exceeds the boundary of the file');
        }

        $str = substr($br->getInputString(), $br->getPosition(), $length);
        $br->setPosition($br->getPosition() + $length);

        return $str;
    }

    /**
     * @param  \PhpBinaryReader\BinaryReader $br
     * @param  int                           $length
     * @return string
     */
    public static function readAligned(BinaryReader &$br, $length)
    {
        $br->align();

        return self::read($br, $length);
    }
}
