<?php

require_once('vendor/autoload.php');

$inputFilePath   = 'files/input/' . $argv[1];
$outputFilePath  = 'files/output/' . $argv[2];
$performanceFile = 'files/performance/performance.html';
$inputContent    = file_get_contents($inputFilePath);


if (!file_exists($inputFilePath)) {
    echo "Error: Input file does not exist.\n";
    exit(1);
}

$converter = new app\Converter($inputContent);

$outputFile = $converter->toXml();

if (file_put_contents($outputFilePath, $outputFile, FILE_APPEND) === false) {
    die("Failed to write to the output file.");
}

if (file_put_contents($performanceFile, $converter->timer()->toTable(), FILE_APPEND) === false) {
    die("Failed to write to the output file.");
}