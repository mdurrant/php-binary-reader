<?php

namespace PhpBinaryReader\Type;

use PhpBinaryReader\BinaryReader;

class Single implements TypeInterface
{
    /**
     * Returns a 4-bytes floating-point
     *
     * @param \PhpBinaryReader\BinaryReader $br
     * @param null $length
     *
     * @return float
     * @throws \OutOfBoundsException
     */
    public function read(BinaryReader &$br, $length = null)
    {
        if (!$br->canReadBytes(4)) {
            throw new \OutOfBoundsException('Cannot read 4-bytes floating-point, it exceeds the boundary of the file');
        }

        $segment = $br->readFromHandle(4);

        if ($br->getCurrentBit() !== 0) {
            $data = unpack('N', $segment)[1];
            $data = $this->bitReader($br, $data);

            $endian = $br->getMachineByteOrder() === $br->getEndian() ? 'N' : 'V';
            $segment = pack($endian, $data);
        } elseif ($br->getMachineByteOrder() !== $br->getEndian()) {
            $segment = pack('N', unpack('V', $segment)[1]);
        }

        $value = unpack('f', $segment)[1];

        return $value;
    }

    /**
     * @param \PhpBinaryReader\BinaryReader $br
     * @param int $data
     *
     * @return int
     */
    private function bitReader(BinaryReader $br, $data)
    {
        $mask = 0x7FFFFFFF >> ($br->getCurrentBit() - 1);
        $value = (($data >> (8 - $br->getCurrentBit())) & $mask) | ($br->getNextByte() << (24 + $br->getCurrentBit()));
        $br->setNextByte($data & 0xFF);

        return $value;
    }
}
