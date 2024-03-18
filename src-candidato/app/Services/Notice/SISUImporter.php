<?php

namespace App\Services\Notice;

use App\Models\Course\CampusOffer;
use App\Models\Course\Course;
use League\Csv\Reader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class SISUImporter {

    private Reader $reader;
    private Collection $fileContent;

    public function readCsvFile(UploadedFile  $file) : void
    {        
        $this->reader = Reader::createFromPath($file->getPathname(), 'r');
        $this->readCSV();
    }

    public function readCsvFileFromPath($path) : void
    {
        $this->reader = Reader::createFromPath($path, 'r');
        $this->readCSV();
    }

    private function readCSV()
    {
        $this->reader->setDelimiter(',');
        $this->reader->setHeaderOffset(0);
        $header = $this->reader->getHeader();
        $this->csvHeader = $header;
        $this->fileContent = collect($this->reader->getRecords($header));
    }
    

    public function importOffersFromFileAndGenerateFeedback() : Collection
    {       
       $offers = $this->getOffersFromFile();
       $availableCourses = CampusOffer::whereIn('sisu_course_code',$offers->pluck('sisu_course_code'))
        ->with(['campus','course'])->get();
       return $offers->map(function($item) use ($availableCourses) {
           $item['course_campus_offer'] = $availableCourses->where('sisu_course_code',$item['sisu_course_code'])->first();
           return $item;
       });
    }

    public function getSubscriptions()
    {        
        return $this->fileContent;
    }

    public function getOffersFromFile() : Collection
    {
        return $this->fileContent->unique(function($item){
            return $item['CO_IES_CURSO'].$item['NO_MODALIDADE_CONCORRENCIA'];
        })->map(function($item) {
            return [
                'sisu_course_code'  => $item['CO_IES_CURSO'], 
                'course'  => $item['NO_CURSO'], 
                'affirmative_action'  => $item['NO_MODALIDADE_CONCORRENCIA'], 
                'total_vacancy' => $item['QT_VAGAS_CONCORRENCIA'],
                'campus' => $item['NO_CAMPUS'],
                'shift' => $item['DS_TURNO'],    
                'course_campus_offer' => null
            ];
        });
    }

}