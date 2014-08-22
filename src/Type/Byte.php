<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;
use PhpBinaryReader\Exception\InvalidDataException;

class Byte implements TypeInterface
{
    /**
     * Returns an variable number of bytes
     *
     * @param  \PhpBinaryReader\BinaryReader $br
     * @param  int|null                      $length
     * @return string
     * @throws \OutOfBoundsException
     * @throws InvalidDataException
     */
    public function read(BinaryReader &$br, $length = null)
    {
        if (!is_int($length)) {
            throw new InvalidDataException('The length parameter must be an integer');
        }

        $br->align();

        if (($br->getPosition() + $length) > $br->getEofPosition()) {
            throw new \OutOfBoundsException('Cannot read bytes, it exceeds the boundary of the file');
        }

        $segment = fread($br->getInputHandle(), $length);

        $br->setPosition($br->getPosition() + $length);

        return $segment;
    }
}
