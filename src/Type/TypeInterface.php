<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;

interface TypeInterface
{
    /**
     * @param \PhpBinaryReader\BinaryReader $br
     * @param int|null                      $length
     */
    public function read(BinaryReader &$br, $length);
}
