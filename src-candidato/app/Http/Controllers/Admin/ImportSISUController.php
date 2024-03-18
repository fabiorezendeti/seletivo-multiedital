<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Process\Notice;

use App\Http\Controllers\Controller;
use App\Services\Notice\SISUImporter;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportSISUController extends Controller
{

    public function index(Notice $notice)
    {
        return view('admin.notices.offers.import', compact('notice'));
    }

    public function store(Notice $notice, Request $request, SISUImporter $SISUImporter)
    {
        try {
            $file = $request->file('sisu_file');
            $folder = $notice->getNoticeSchemaName();
            $uuid = ($request->uuid) ?? Str::uuid();
            $fileName =  'sisu_' . $uuid . '.' . $file->getClientOriginalExtension();
            $file->storeAs($folder, $fileName, 'documents');
            $SISUImporter->readCsvFile($file);
            $offers = $SISUImporter->importOffersFromFileAndGenerateFeedback();
            return view('admin.notices.offers.import-check', compact('notice', 'offers', 'fileName'));
        } catch (Exception $exception) {
            Log::error('Erro ao importar arquivo do SISU ' . $exception->getMessage(), ['SISU', 'Ofertas']);
            return redirect()->back()->with('error', 'Um erro ocorreu ao ler o arquivo, certifique que esteja no formato correto e tenha os campos necessários');
        }
    }
}
