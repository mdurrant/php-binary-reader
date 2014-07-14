<?php

namespace PhpBinaryReader;

class Endian
{
    const ENDIAN_BIG = 1;
    const ENDIAN_LITTLE = 2;

    /**
     * Converts the endianess of a number from big to little or vise-versa
     *
     * @param  int $value
     * @return int
     */
    public static function convert($value)
    {
        $data = dechex($value);

        if (strlen($data) <= 2) {
            return $value;
        }

        $unpack = unpack("H*", strrev(pack("H*", $data)));
        $converted = hexdec($unpack[1]);

        return $converted;
    }
}
