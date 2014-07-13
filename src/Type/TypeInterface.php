<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;

interface TypeInterface
{
    /**
     * @param \PhpBinaryReader\BinaryReader $br
     * @param int|null $length
     */
    public static function read(BinaryReader &$br, $length);
}
