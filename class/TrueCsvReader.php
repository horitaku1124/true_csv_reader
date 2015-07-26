<?php

class TrueCsvReader {
    const BUFFER_SIZE = 1000000;
    const WINDOWS_FORMAT = 1;
    const MAC_FORMAT = 2;

    /**
     * @var resource
     */
    protected $handle;
    protected $endOfLine;
    protected $delimiter;
    protected $buffer;
    protected $bufferPoint;
    public function __construct($filePath, $readAs = self::WINDOWS_FORMAT)
    {
        $this->filePath = $filePath;
        switch($readAs) {
            case self::WINDOWS_FORMAT:
                $this->endOfLine = "\r\n";
                $this->endOfLine = ",";
                break;
            case self::MAC_FORMAT:
                $this->endOfLine = "\r";
                $this->endOfLine = ",";
                break;
            default:
                $this->endOfLine = "\r\n";
                $this->endOfLine = ",";
                break;
        }
        $this->handle = fopen($this->filePath, "rb");
        $this->buffer = '';
        $this->bufferPoint = 0;
    }

    public function readLine()
    {
        while(true) {
            $buffer = fread($this->handle, self::BUFFER_SIZE);
            if($buffer === false || $buffer === "") break;

            // TODO implement later.

            yield $buffer;
        }
    }

    public function close()
    {
        fclose($this->handle);
    }
}