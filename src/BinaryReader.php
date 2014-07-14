<?php

namespace PhpBinaryReader;

use PhpBinaryReader\Exception\InvalidDataException;
use PhpBinaryReader\Type\Bit;
use PhpBinaryReader\Type\Int8;
use PhpBinaryReader\Type\Int16;
use PhpBinaryReader\Type\Int32;
use PhpBinaryReader\Type\String;

class BinaryReader
{
    /**
     * @var int
     */
    private $machineByteOrder = Endian::ENDIAN_LITTLE;

    /**
     * @var string
     */
    private $inputString;

    /**
     * @var int
     */
    private $currentBit;

    /**
     * @var mixed
     */
    private $nextByte;

    /**
     * @var int
     */
    private $position;

    /**
     * @var int
     */
    private $eofPosition;

    /**
     * @var string
     */
    private $endian;

    /**
     * @param  string                    $str
     * @param  int|string                $endian
     * @throws \InvalidArgumentException
     */
    public function __construct($str, $endian = Endian::ENDIAN_LITTLE)
    {
        $this->eofPosition = strlen($str);

        $this->setEndian($endian);
        $this->setInputString($str);
        $this->setNextByte(false);
        $this->setCurrentBit(0);
        $this->setPosition(0);
    }

    /**
     * @return bool
     */
    public function isEof()
    {
        if ($this->getPosition() >= $this->getEofPosition()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return void
     */
    public function align()
    {
        $this->setCurrentBit(0);
        $this->setNextByte(false);
    }

    /**
     * @param  int $count
     * @return int
     */
    public function readBits($count)
    {
        return Bit::readSigned($this, $count);
    }

    /**
     * @param  int $count
     * @return int
     */
    public function readUBits($count)
    {
        return Bit::read($this, $count);
    }

    /**
     * @return int
     */
    public function readInt8()
    {
        return Int8::readSigned($this);
    }

    /**
     * @return int
     */
    public function readUInt8()
    {
        return Int8::read($this);
    }

    /**
     * @return int
     */
    public function readInt16()
    {
        return Int16::readSigned($this);
    }

    /**
     * @return string
     */
    public function readUInt16()
    {
        return Int16::read($this);
    }

    /**
     * @return int
     */
    public function readInt32()
    {
        return Int32::readSigned($this);
    }

    /**
     * @return int
     */
    public function readUInt32()
    {
        return Int32::read($this);
    }

    /**
     * @param  int    $length
     * @return string
     */
    public function readString($length)
    {
        return String::read($this, $length);
    }

    /**
     * @param  int    $length
     * @return string
     */
    public function readAlignedString($length)
    {
        return String::readAligned($this, $length);
    }

    /**
     * @param  int   $machineByteOrder
     * @return $this
     */
    public function setMachineByteOrder($machineByteOrder)
    {
        $this->machineByteOrder = $machineByteOrder;

        return $this;
    }

    /**
     * @return int
     */
    public function getMachineByteOrder()
    {
        return $this->machineByteOrder;
    }

    /**
     * @param  string $inputString
     * @return $this
     */
    public function setInputString($inputString)
    {
        $this->inputString = $inputString;

        return $this;
    }

    /**
     * @return string
     */
    public function getInputString()
    {
        return $this->inputString;
    }

    /**
     * @param  mixed $nextByte
     * @return $this
     */
    public function setNextByte($nextByte)
    {
        $this->nextByte = $nextByte;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNextByte()
    {
        return $this->nextByte;
    }

    /**
     * @param  int   $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getEofPosition()
    {
        return $this->eofPosition;
    }

    /**
     * @param  string               $endian
     * @return $this
     * @throws InvalidDataException
     */
    public function setEndian($endian)
    {
        if ($endian == 'big' || $endian == Endian::ENDIAN_BIG) {
            $this->endian = Endian::ENDIAN_BIG;
        } elseif ($endian == 'little' || $endian == Endian::ENDIAN_LITTLE) {
            $this->endian = Endian::ENDIAN_LITTLE;
        } else {
            throw new InvalidDataException('Endian must be set as big or little');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getEndian()
    {
        return $this->endian;
    }

    /**
     * @param  int   $currentBit
     * @return $this
     */
    public function setCurrentBit($currentBit)
    {
        $this->currentBit = $currentBit;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentBit()
    {
        return $this->currentBit;
    }
}
