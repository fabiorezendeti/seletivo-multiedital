<?php

namespace App\Services\CsvLib;

use League\Csv\Writer;
use League\Csv\EncloseField;
use Illuminate\Database\Eloquent\Collection;
use App\ExternalLibContracts\PhpLeague\CsvWriter;
use App\ExternalLibContracts\PhpLeague\SplTempFileObject;
use App\Services\CsvLib\Interfaces\CsvWriter as InterfacesCsvWriter;

class CsvFileService implements InterfacesCsvWriter {

    private $csvLeague;

    public function __construct($SplTemp = true)
    {
        if ($SplTemp) {
            $this->csvLeague = Writer::createFromFileObject(new \SplTempFileObject());
        } else {
            $this->csvLeague = Writer::createFromPath('php://temp', 'r+');
        }
    }


    public function insertOne(array $line)
    {
        $this->csvLeague->insertOne($line);
    }


    public function output($filename)
    {
        $this->csvLeague->output($filename);
    }

    public function setDelimiter($delimiter= ","){
        $this->csvLeague->setDelimiter($delimiter);
    }

    public function forceEncloseField()
    {
        EncloseField::addTo($this->csvLeague, "\t\x1f");
    }

}