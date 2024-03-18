<?php

namespace App\Services\Subscription;

use App\Models\Process\Subscription;

class BoletimService
{

    public function download(Subscription $subscription)
    {
        $noticeFolder = $subscription->notice->getNoticeSchemaName();
        $document = $subscription->getScore()->documento_comprovacao;
        $file = storage_path('app/' . $noticeFolder . '/' . $document);        
        return response()->file($file,[
            'PRAGMA'=>'NO-CACHE'
        ]);
    }
}
