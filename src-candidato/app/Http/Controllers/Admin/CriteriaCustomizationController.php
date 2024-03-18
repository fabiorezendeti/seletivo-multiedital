<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Process\Notice;
use App\Http\Controllers\Controller;
use App\Models\Process\SelectionCriteria;

class CriteriaCustomizationController extends Controller
{
    
    public function show(Notice $notice, $criteria)
    {
        $criteria = $notice->selectionCriterias->where('id',$criteria)->first();        
        return view('admin.notices.customizes.edit',compact('notice','criteria'));
    }


}
