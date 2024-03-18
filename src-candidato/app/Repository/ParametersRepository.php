<?php

namespace App\Repository;

use App\Models\Pagtesouro\Parameters;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParametersRepository
{


    public function getValueByName(string $name)
    {
        return DB::table('parameters')
            ->where('name',$name)
            ->first()
            ->value ?? null;
    }

    public function getPagTesouroParameters() : Parameters
    {
        try {
            $attributes = DB::table('parameters')->where('name', 'like','pagtesouro_%')->get();
            $parameters = new Parameters();
            $parameters->setFromCollection($attributes);
            return $parameters;
        } catch (Exception $exception) {
            Log::error('');
            return abort(500, 'Erro ao setar parâmetros do PagTesouro');
        }
        
    }

    public function setPagTesouroParameters($data)
    {
        try {
            foreach ($data as $key => $value) {
                DB::table('parameters')->where('name','like',$key)
                ->update(['value' => $value]);
            }
            return;
        } catch (Exception $exception) {
            Log::error('');
            return abort(500, 'Erro ao atualizar parâmetros do PagTesouro');
        }
        
    }

}