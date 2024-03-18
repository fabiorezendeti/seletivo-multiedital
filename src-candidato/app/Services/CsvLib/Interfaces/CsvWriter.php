<?php

namespace App\Services\CsvLib\Interfaces;

use League\Csv\Writer;

interface CsvWriter {

    public function insertOne(array $line);

    public function output($filename);

    public function setDelimiter($delimiter);

}