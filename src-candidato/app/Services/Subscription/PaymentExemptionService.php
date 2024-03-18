<?php

namespace App\Services\Subscription;

use App\Models\Process\Subscription;

class PaymentExemptionService
{

    public function downloadDocumentIdFront(Subscription $subscription)
    {
        $noticeFolder = $subscription->notice->getNoticeSchemaName();
        $paymentExemptionDocument = $subscription->paymentExemption->document_id_front;
        $file = storage_path('app/' . $noticeFolder . '/' . $paymentExemptionDocument);
        return response()->file($file,[
            'PRAGMA'=>'NO-CACHE'
        ]);
    }

    public function downloadDocumentIdBack(Subscription $subscription)
    {
        $noticeFolder = $subscription->notice->getNoticeSchemaName();
        $paymentExemptionDocument = $subscription->paymentExemption->document_id_back;
        $file = storage_path('app/' . $noticeFolder . '/' . $paymentExemptionDocument);
        return response()->file($file,[
            'PRAGMA'=>'NO-CACHE'
        ]);
    }

    public function downloadDocumentForm(Subscription $subscription)
    {
        $noticeFolder = $subscription->notice->getNoticeSchemaName();
        $paymentExemptionDocument = $subscription->paymentExemption->document_form;
        $file = storage_path('app/' . $noticeFolder . '/' . $paymentExemptionDocument);
        return response()->file($file,[
            'PRAGMA'=>'NO-CACHE'
        ]);
    }

    public function downloadTxt(Subscription $subscription)
    {
        $noticeFolder = $subscription->notice->getNoticeSchemaName();
        $paymentExemptionDocument = $subscription->paymentExemption->document_form;
        $file = storage_path('app/' . $noticeFolder . '/' . $paymentExemptionDocument);
        return response()->file($file,[
            'PRAGMA'=>'NO-CACHE'
        ]);
    }
}
