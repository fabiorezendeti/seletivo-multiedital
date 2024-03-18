<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Process\Notice;
use App\Services\Subscription\BoletimService;
use App\Http\Controllers\Controller;
use App\Models\Process\Subscription;


class DocumentController extends Controller
{
    public function viewBoletim(Notice $notice, Subscription $subscription, BoletimService $boletimService)
    {
        try {            
            return $boletimService->download($subscription);            
        } catch (Exception $exception) {
            return abort('404');
        }                
    }
}
