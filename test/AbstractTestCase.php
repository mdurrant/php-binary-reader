<?php

namespace PhpBinaryReader;

class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    // Data provider for creating both File and String based binary readers.
    public function binaryReaders()
    {
        $fileBig = __DIR__ . '/asset/testfile-big.bin';
        $fileLittle = __DIR__ . '/asset/testfile-little.bin';

        $dataBig = fopen($fileBig, 'rb');
        $dataLittle = fopen($fileLittle, 'rb');

        $brBigFile = new BinaryReader(fopen($fileBig, 'rb'), Endian::ENDIAN_BIG);
        $brLittleFile = new BinaryReader(fopen($fileLittle, 'rb'), Endian::ENDIAN_LITTLE);

        $brBigStr = new BinaryReader(file_get_contents($fileBig), Endian::ENDIAN_BIG);
        $brLittleStr = new BinaryReader(file_get_contents($fileLittle), Endian::ENDIAN_LITTLE);

        return [
            [$brBigFile, $brLittleFile],
            [$brBigStr, $brLittleStr],
        ];
    }
}
