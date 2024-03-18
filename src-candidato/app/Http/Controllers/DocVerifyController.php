<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocVerifyController extends Controller
{
    
    public function vacancy(string $hash)
    {        
        try {
            $html = Storage::disk('documents')->get("vacancy/$hash.html");            
            return view('candidate.verify-document',compact('html'));
        } catch (Exception $exception) {
            return abort(404, 'O arquivo não existe');
        }        
    }

}
