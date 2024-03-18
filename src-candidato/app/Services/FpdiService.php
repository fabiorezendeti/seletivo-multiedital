<?php

namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;






class FpdiService {
    

    public function getFpdi(){
        return new Fpdi();        
    }
    
}