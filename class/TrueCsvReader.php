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
    protected $fileEncode;
    protected $buffer;
    protected $bufferPoint;
    public function __construct($filePath, $readAs = self::WINDOWS_FORMAT)
    {
        $this->filePath = $filePath;
        switch($readAs) {
            case self::WINDOWS_FORMAT:
                $this->endOfLine = "\r\n";
                $this->delimiter = ",";
                $this->fileEncode = "sjis";
                break;
            case self::MAC_FORMAT:
                $this->endOfLine = "\r";
                $this->delimiter = ",";
                $this->fileEncode = "sjis";
                break;
            default:
                $this->endOfLine = "\r\n";
                $this->delimiter = ",";
                $this->fileEncode = "sjis";
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
            $start = 0;
            $index = 0;
            $row = [];
            for($i = 0;$i < strlen($buffer);$i++) {
                $c = $buffer[$i];
                if($c == "\r") {
                    $col = substr($buffer, $start, $index - $start);
                    $row2 = $row;
                    $row2[] = $col;
                    $index = $i + 1;
                    $i++;
                    $start = $index;
                    $row = [];
                    yield $row2;
                }
                if($c == $this->delimiter) {
                    $col = substr($buffer, $start, $index - $start);
                    $row[] = $col;
                    $index = $i + 1;
                    $i++;
                    $start = $index;
                }
                $index++;
            }
            if($start < strlen($buffer) - 1) {
                $col = substr($buffer, $start);
                $row[] = $col;
                yield $row;
            }
        }
    }

    public function close()
    {
        fclose($this->handle);
    }
}