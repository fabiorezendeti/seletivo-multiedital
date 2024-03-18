<?php

namespace App\Actions\Audit;

use App\Models\Audit\Justify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;


class JustifyUpdates
{

    /**
     * Create a new justify model
     * @param \Illuminate\Database\Eloquent\Model $model        
     */
    public static function create(Model $model) 
    {                
        $request = request();
        Justify::create(
            [
                'justify' => $request->input('justify_text'),
                'data' => $model->toJson(),
                'author_id' => Auth::id(),
                'uri' => $request->getUri()
            ]                
        )->save();
    }

    /**
     * Create a new justify model
     * @param array $data       
     */
    public static function createFromArray(array $data)
    {
        $request = request();
        Justify::create(
            [
                'justify' => $request->input('justify_text'),
                'data' => json_encode($data),
                'author_id' => Auth::id(),
                'uri' => $request->getUri()
            ]                
        )->save();
    }

    
    
}