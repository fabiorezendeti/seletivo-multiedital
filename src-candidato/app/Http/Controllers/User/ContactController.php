<?php

namespace App\Http\Controllers\User;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    
    public function edit()
    {
        return view('profile.contact');
    }


    public function update(Request $request, UpdateUserProfileInformation $updateUserProfileInformation)
    {        
        $user = Auth::user();
        $data = $request->all();
        $data['has_whatsapp'] = $data['has_whatsapp'] ?? false;
        $data['has_telegram'] = $data['has_telegram'] ?? false;
        $data['city_id'] = $data['city'];
        $updateUserProfileInformation->updateContactData($user,$data);
        return redirect()->back()
            ->with('success','Seus dados de CONTATO foram atualizados com sucesso');
    }

}