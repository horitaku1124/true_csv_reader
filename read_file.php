<?php
require 'class/TrueCsvReader.php';

if($argc == 1) exit('Specify file path.');

$reader = new TrueCsvReader($argv[1]);
foreach($reader->readLine() as $line) {
    print_r(str_replace("\r", "\\r", $line));
}
$reader->close();