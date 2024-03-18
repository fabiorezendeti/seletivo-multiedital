<?php

namespace App\Repository;

use App\Models\Process\AffirmativeAction;
use Illuminate\Support\Facades\DB;
use App\Models\Process\DocumentType;
use App\Models\Process\Subscription;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use League\CommonMark\Node\Block\Document;

class EnrollmentProcessRepository
{


    public function getBySubscription(Subscription $subscription, $callNumber = null, $enrollmentId = null)
    {
        try {
            $table = $subscription->notice->getEnrollmentProcessTableName();
            $callTable = $subscription->notice->getEnrollmentCallTableNameByCriteria($subscription->distributionOfVacancy->selectionCriteria);
            $query = DB::table("$table as enroll")
                ->select(
                    'enroll.*',
                    DB::raw("TO_CHAR(enroll.send_at, 'DD/MM/YY HH24:II') as send_at_pt_br"),
                    DB::raw("(SELECT status FROM $callTable as call where subscription_id = $subscription->id and call.call_number = enroll.call_number) as status")
                )
                ->where('enroll.subscription_id', $subscription->id)
                ->orderBy('send_at', 'desc');
            if ($callNumber) {
                return $query->where('enroll.call_number', $callNumber)->first();
            }
            if ($enrollmentId) {
                return $query->where('enroll.id', $enrollmentId)->first();
            }
            return $query->get();
        } catch (QueryException $exception) {
            return collect();
        }
    }

    public function getSendedDocuments(Collection $enrollmentProcess, Subscription $subscription)
    {
        try {
            $table = $subscription->notice->getEnrollmentProcessDocumentsTableName();
            $query = DB::table($table)
                ->whereIn('enrollment_process_id', $enrollmentProcess->pluck('id'));
            return $query->get();
        } catch (QueryException $exception) {
            return collect();
        }
    }

    public function getSendedDocumentByEnrollmentProcessIdAndDocumentTypeId($id, $documentId, Subscription $subscription)
    {

        $table = $subscription->notice->getEnrollmentProcessDocumentsTableName();
        $query = DB::table($table)
            ->where('enrollment_process_id', $id)
            ->where('document_type_id', $documentId);
        return $query->get();
    }

    public function getSendedDocumentByEnrollmentProcessIdAndDocumentId($id, $documentId, Subscription $subscription)
    {
        $table = $subscription->notice->getEnrollmentProcessDocumentsTableName();
        $query = DB::table($table)
            ->where('enrollment_process_id', $id)
            ->where('id', $documentId);
        return $query->get();
    }

    public function getSendedDocumentByEnrollmentProcessIdAndDocumentUuid($id, $uuid, Subscription $subscription)
    {
        $table = $subscription->notice->getEnrollmentProcessDocumentsTableName();
        $query = DB::table($table)
            ->where('enrollment_process_id', $id)
            ->where('uuid', $uuid);
        return $query->first();
    }

    public function deleteDocumentByUuid($id, $uuid, Subscription $subscription)
    {
        $table = $subscription->notice->getEnrollmentProcessDocumentsTableName();
        $query = DB::table($table)
            ->where('enrollment_process_id', $id)
            ->where('uuid', $uuid);
        return $query->delete();
    }

    public function getSendedDocumentByEnrollmentProcessId($id, Subscription $subscription)
    {
        $table = $subscription->notice->getEnrollmentProcessDocumentsTableName();
        $query = DB::table($table)
            ->where('enrollment_process_id', $id)
            ->orderBy('document_type_id');
        return $query->get();
    }

    public function getBySubscriptionAndCallNumber(Subscription $subscription, int $callNumber)
    {
        return $this->getBySubscription($subscription, $callNumber);
    }

    public function getBySubscriptionAndEnrollmentProcessId(Subscription $subscription, int $enrollmentId)
    {
        return $this->getBySubscription($subscription, null, $enrollmentId);
    }

    public function makeEnroll(Subscription $subscription, int $callNumber, $finished = null)
    {        
        $table = $subscription->notice->getEnrollmentProcessTableName();
        DB::table($table)->updateOrInsert(
            ['subscription_id' => $subscription->id, 'call_number' => $callNumber],
            ['send_at' => $finished]
        );
    }

    public function saveDocs(Subscription $subscription, DocumentType $documentType, $enrollmentProcessId, $documentTitle, $path, $file, $documentId)
    {
        $docsTable = $subscription->notice->getEnrollmentProcessDocumentsTableName();
        $data = [
            'document_type_id'         => $documentType->id,
            'document_type'         => $documentType->field_name,
            'document_title'        => $documentTitle,
            'enrollment_process_id' => $enrollmentProcessId,
            'uuid'                  => $documentId,
            'feedback'              => null,
            'url'   => route('candidate.subscription.enrollment-process.view-document', [
                'subscription' => $subscription,
                'enrollment_process' => $enrollmentProcessId,
                'documentId'    => $documentId
            ]),
            'path' => $path,
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
        ];
        DB::table($docsTable)->updateOrInsert(
            [
                'enrollment_process_id' => $enrollmentProcessId,
                'uuid' => $documentId
            ],
            $data
        );
        return DB::table($docsTable)
            ->where('uuid', $documentId)->first();
    }

    public function getDocumentsNeeds(int $affirmativeActionId = null, Collection $sendedDocuments = null): Collection
    {
        $documentTypes = DocumentType::ordered()
            ->contextEnrollment()
            ->allDocumentsNeeded($affirmativeActionId)->get();
        
        return $documentTypes;
        if (!$sendedDocuments) return $documentTypes;

        $negativeFeedback = $sendedDocuments->whereNotNull('feedback');
        if ($negativeFeedback->count() == 0) return $documentTypes;

        $negatives = $documentTypes->whereIn('id', $negativeFeedback->pluck('document_type_id'));
        $missing = $documentTypes->whereNotIn('id', $sendedDocuments->pluck('document_type_id'));
        return $negatives->union($missing);
    }
}
