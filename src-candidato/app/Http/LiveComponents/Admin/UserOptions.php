<?php

namespace App\Http\LiveComponents\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserOptions extends Component
{

    use AuthorizesRequests;

    public $userState;
    protected $current_password;
    

    public function mount(User $user)
    {
        $this->userState = $user->withoutRelations()->toArray();   
        $this->userState['two_factor_secret'] = $user->two_factor_secret;    
    }

    public function validateEmail()
    {
        $this->authorize('isAdmin');
        $dateTime =  now();        
        User::findOrFail($this->userState['uuid'])->forceFill(['email_verified_at'=>$dateTime])->save();
        $this->userState['email_verified_at'] = $dateTime;
    }

    public function changePassword()
    {
        $this->authorize('isAdmin');
        
        $this->current_password = $this->generatePassword();
        User::findOrFail($this->userState['uuid'])->forceFill(['password'=>
            Hash::make($this->current_password)])->save();                
    }

    public function removeTwoFactorAuthentication()
    {
        $this->authorize('isAdmin');
        $dateTime =  now();        
        User::findOrFail($this->userState['uuid'])->forceFill([
            'two_factor_secret'         => null,
            'two_factor_recovery_codes' => null
        ])->save();
        $this->userState['two_factor_secret'] = null;
    }
    

    public function render()
    {
        
        return view('live-components.admin.user-options',[
            'newPassword' => $this->current_password
        ]);
    }

    private function generatePassword()
    {
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $number = '1234567890';
        
        $chars = $lower.$upper.$number;
        $len =strlen($chars);
        $newPassword = '';
        for ($i = 1; $i <= 8; $i++) {
            $rand = mt_rand(1, $len);
            $newPassword .= $chars[$rand-1];
        }
        return $newPassword;
    }

}