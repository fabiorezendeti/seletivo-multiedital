<?php

namespace App\Http\Controllers\Candidate;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Process\Subscription;
use Illuminate\Support\Facades\Auth;
use App\Models\Process\PaymentExemption;
use App\Http\Requests\StorePaymentExemption;
use App\Services\Subscription\PaymentExemptionService;

class PaymentExemptionController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Subscription $subscription)
    {
        $uploadMaxSize = (int) ini_get("upload_max_filesize") - 1;
        $paymentExemption = new PaymentExemption();
        return view('candidate.subscription.payment-exemption.create', compact('uploadMaxSize', 'subscription', 'paymentExemption'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreNotice  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Subscription $subscription, StorePaymentExemption $request)
    {
        $fileIdFront = $request->file('document_id_front');
        $fileIdBack = $request->file('document_id_back');
        $fileForm = $request->file('document_form');

        if ($request->hasFile('document_id_front')) {
            $fileNameIdFront = $subscription->id . '.id_front.' . $fileIdFront->getClientOriginalExtension();
            $fileIdFront->storeAs($subscription->notice->getNoticeSchemaName(), $fileNameIdFront);
        }

        if ($request->hasFile('document_id_back')) {
            $fileNameIdBack = $subscription->id . '.id_back.' . $fileIdBack->getClientOriginalExtension();
            $fileIdBack->storeAs($subscription->notice->getNoticeSchemaName(), $fileNameIdBack);
        }

        if ($request->hasFile('document_form')) {
            $fileNameForm = $subscription->id . '.form.' . $fileForm->getClientOriginalExtension();
            $fileForm->storeAs($subscription->notice->getNoticeSchemaName(), $fileNameForm);
        }

        $paymentExemption = PaymentExemption::create(['document_id_front' => $fileNameIdFront,
                                                      'document_id_back'  => $fileNameIdBack,
                                                      'document_form'     => $fileNameForm]);

        $subscription->paymentExemption()->associate($paymentExemption);
        $subscription->save();

        return redirect()->route('candidate.subscription.show', ['subscription' => $subscription]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        return view('candidate.subscription.payment-exemption.show', compact('subscription'));
    }

    public function viewDocumentIdFront(Subscription $subscription, PaymentExemptionService $paymentExemptionService)
    {
        try {
            return $paymentExemptionService->downloadDocumentIdFront($subscription);
        } catch (Exception $exception) {
            return abort('404');
        }
    }

    public function viewDocumentIdBack(Subscription $subscription, PaymentExemptionService $paymentExemptionService)
    {
        try {
            return $paymentExemptionService->downloadDocumentIdBack($subscription);
        } catch (Exception $exception) {
            return abort('404');
        }
    }

    public function viewDocumentForm(Subscription $subscription, PaymentExemptionService $paymentExemptionService)
    {
        try {
            return $paymentExemptionService->downloadDocumentForm($subscription);
        } catch (Exception $exception) {
            return abort('404');
        }
    }

}
