<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process\Notice;
use App\Services\Financial\GRUFileService;
use App\Services\Financial\GRUPayment;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;

class ReadGruFileController extends Controller
{

    public function index(Notice $notice)
    {
        return view('admin.notices.financial.read-gru-file', compact('notice'));
    }

    public function store(Request $request, Notice $notice, GRUFileService  $GRUFileService)
    {
        $mimeTypes = 'xml';
        $rules = ['required', 'file', "mimes:$mimeTypes"];
        $file = $request->file('gru_document');                
        $this->validate($request, [
            'gru_document' => $rules,
        ], ['gru_document.mimes' => 'Um erro ocorreu ao carregar o arquivo, verifique se o tamanho e formato do arquivo correspondem ao correto'], 
        ['gru_document'   => 'Arquivo de GRU']);
        $GRUFileService->processXML($file, $notice);
        $notFound = $GRUFileService->getNotFoundList();
        $previousHomologate = $GRUFileService->getPreviousHomologate();
        $payments = $GRUFileService->getPaymentList();
        $errors = $GRUFileService->getErrorList();
        return view('admin.notices.financial.gru-file-check',compact(
            'notice','previousHomologate', 'notFound', 'payments', 'errors'
        ));
    }
}
